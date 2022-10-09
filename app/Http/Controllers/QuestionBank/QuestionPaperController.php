<?php

namespace App\Http\Controllers\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuestionPaper;
use App\Models\QuestionPaperQuestion;
use Illuminate\Http\Request;

class QuestionPaperController extends Controller
{
    public function get_all()
    {
        return QuestionPaper::select(['id', 'name'])->where('status', 1)->orderBy('name', 'ASC')->get();
    }

    public function all(Request $request)
    {
        $query = QuestionPaper::where('status', 1)->orderBy('id', 'DESC')
            ->with([
                'module' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'chapter' => function ($q) {
                    return $q->select(['id', 'chapter_name']);
                },
                'questions' => function ($q) {
                    return $q->select('*');
                },
            ])
            ->withCount(['questions']);
        if ($request->has('key') && strlen($request->key) > 0) {
            $key = $request->key;
            $query->where(function ($q) use ($key) {
                $q->where('name', $key)
                    ->orWhere('id', $key)
                    ->orWhere('name', 'LIKE', '%' . $key . '%')
                    ->orWhere('module_id', $key)
                    ->orWhere('chapter_id', $key)
                    // ->orWhere(function ($q) use ($key) {
                    //     return $q->whereExists(function ($q) use ($key) {
                    //         return $q->select('name')
                    //             ->from('modules')
                    //             ->where('modules.name', 'LIKE', '%' . $key . '%');
                    //     });
                    // })
                ;
            });
        }
        $data = $query->paginate(env('PAGINATE'));
        return response()->json($data);
    }

    public function get(QuestionPaper $question_paper)
    {
        $question_paper->module = $question_paper->module()->select('id','name')->first();
        $question_paper->chapter = $question_paper->chapter()->select('id','chapter_name')->first();
        $question_paper->questions = $question_paper->questions()
            ->with([
                'question' => function ($q) {
                    $q->withCount('used_question');
                },
            ])
            ->get();
        $selected_question = [];
        $selected_question_list = [];

        foreach ($question_paper->questions as $item) {
            $selected_question[] = $item->question_id;

            $options = [];
            $options[] = $item->question->option_1;
            $options[] = $item->question->option_2;
            $options[] = $item->question->option_3;
            $options = collect($options)->shuffle();

            $item->question->option_1 = $options[0];
            $item->question->option_2 = $options[1];
            $item->question->option_3 = $options[2];

            $selected_question_list[] = $item->question;
        }
        // dd($selected_question , $selected_question_list);
        $question_paper->selected_question = $selected_question;
        $question_paper->selected_question_list = collect($selected_question_list)->shuffle();
        return response()->json($question_paper);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'module_id' => ['required'],
            'chapter_id' => ['required'],
            'okay' => ['required'],
            'selected_question' => ['min:3', 'required'],
        ], [
            'module_id.required' => 'The module field is required',
            'chapter_id.required' => 'The chapter field is required',
            'selected_question.min' => 'There is no question selected',
        ]);

        $data = QuestionPaper::create(request()->except('selected_question'));
        $data->creator = auth()->user()->id;
        $data->save();

        foreach (json_decode(request()->selected_question) as $value) {
            $question_paper_question = new QuestionPaperQuestion();
            $question_paper_question->question_paper_id = $data->id;
            $question_paper_question->question_id = $value;
            $question_paper_question->creator = auth()->user()->id;
            $question_paper_question->save();
        }

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'module_id' => ['required'],
            'chapter_id' => ['required'],
            'okay' => ['required'],
            'selected_question' => ['min:3', 'required'],
        ], [
            'module_id.required' => 'The module field is required',
            'chapter_id.required' => 'The chapter field is required',
            'selected_question.min' => 'There is no question selected',
        ]);

        $data = QuestionPaper::find(request()->id);
        $data->fill(request()->except('selected_question'));
        $data->creator = auth()->user()->id;
        $data->save();

        QuestionPaperQuestion::where('question_paper_id', $data->id)->delete();
        foreach (json_decode(request()->selected_question) as $value) {
            $question_paper_question = new QuestionPaperQuestion();
            $question_paper_question->question_paper_id = $data->id;
            $question_paper_question->question_id = $value;
            $question_paper_question->creator = auth()->user()->id;
            $question_paper_question->save();
        }

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $data = QuestionPaper::find($request->id);
        if($data){
            QuestionPaperQuestion::where('question_paper_id', $data->id)->delete();
            $data->status = 0;
            $data->save();
        }
        // return 1;
        return $this->all($request);
    }

    public function export(Request $request)
    {
        $fp = fopen(public_path('export.csv'), 'w');
        fputcsv($fp, [
            'module',
            'chapter',
            'okay',
            'question_paper_title',
            'question_pattern',
            'question_title',
            'option_1',
            'option_2',
            'option_3',
            'answer',
            'reference',
            'level',
        ]);
        if(request()->has('get_data_by_id') && $request->get_data_by_id){
            $data =  $this->get(QuestionPaper::find($request->get_data_by_id))->getData();
            // dd($data->getData());
        }else{
            $data = (object) $request->data;
        }
        foreach ($data->questions as $question) {
            $data->module = (object) $data->module;
            $data->chapter = (object) $data->chapter;
            $question = (object) $question;
            $question->question = (object) $question->question;
            $fields = [
                $data->module->name,
                $data->chapter->chapter_name,
                $data->okay,
                $data->name,
                $question->question->question_pattern,
                $question->question->question_title,
                $question->question->option_1,
                $question->question->option_2,
                $question->question->option_3,
                $question->question->answer,
                $question->question->reference,
                $question->question->level,
            ];
            fputcsv($fp, $fields);
        }
        fclose($fp);

    }

    public function load_csv(Request $request)
    {
        ini_set('memory_limit', '999999M');

        if ($request->hasFile('file')) {
            $row = 1;
            $modules = [];
            if (($handle = fopen($request->file('file'), "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 999999, ",")) !== FALSE) {
                    if ($row > 1 && count($data)) {
                        $temp = [
                            'module' => $data[0],
                            'chapter' => $data[1],
                            'okay' => $data[2],
                            'question_paper_title' => $data[3],
                            'question_title' => $data[4],
                        ];
                        $modules[] = $temp;
                    }
                    $row++;
                }
                fclose($handle);

                return response()->json($modules);
            }
        }
    }

    public function store_csv(Request $request)
    {
        $datas = collect($request->data);
        $question_papers = $datas->unique('question_paper_title');

        foreach ($question_papers as $item) {

            $data = new QuestionPaper();
            if (Module::where('name', 'LIKE', '%' . $item['module'] . '%')->exists()) {
                $data->module_id = Module::where('name', 'LIKE', '%' . $item['module'] . '%')->first()->id;
            } else {
                $module = Module::create([
                    'name' => $item['module'],
                    'creator' => auth()->user()->id,
                ]);
                $data->module_id = $module->id;
            }
            if (Chapter::where('chapter_name', 'LIKE', '%' . $item['chapter'] . '%')->exists()) {
                $data->chapter_id = Chapter::where('chapter_name', 'LIKE', '%' . $item['chapter'] . '%')->first()->id;
            } else {
                $chapter = Chapter::create([
                    'module_id' => $data->module_id,
                    'chapter_name' => $item['chapter'],
                    'creator' => auth()->user()->id,
                ]);
                $data->chapter_id = $chapter->id;
            }
            $data->name = $item['question_paper_title'];
            $data->okay = $item['okay'];
            $data->creator = auth()->user()->id;
            $data->save();

            $data_question_paper_questions = $datas->where('question_paper_title', $item['question_paper_title'])->all();
            foreach ($data_question_paper_questions as $question) {
                if (Question::where('question_title', 'LIKE', '%' . $question['question_title'] . '%')->exists()) {
                    $question_paper_question = new QuestionPaperQuestion();
                    $question_paper_question->question_paper_id = $data->id;

                    $question = Question::where('question_title', 'LIKE', '%' . $question['question_title'] . '%')->first();
                    $question_paper_question->question_id = $question->id;
                    $question_paper_question->save();
                }
            }
        }
        return response()->json('data uploaded');
    }
}
