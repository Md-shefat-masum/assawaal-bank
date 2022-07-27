<?php

namespace App\Http\Controllers\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuestionPaper;
use App\Models\QuestionPaperQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isNull;

class QuestionController extends Controller
{
    public function active_modules()
    {
        $active_modules = Module::select('id', 'status')->where('status', 1)->get();
        $data = [];
        foreach ($active_modules as $item) {
            $data[] = $item->id;
        }
        return $data;
    }

    public function active_chapters()
    {
        $active_chapters = Chapter::select('id', 'module_id', 'status')
            ->whereIn('module_id', $this->active_modules())
            ->where('status', 1)->get();
        $data = [];
        foreach ($active_chapters as $item) {
            $data[] = $item->id;
        }
        return $data;
    }

    public function get_all()
    {
        return Question::select(['id', 'question_title'])
            ->where('status', 1)
            ->whereIn('module_id', $this->active_modules())
            ->whereIn('chapter_id', $this->active_chapters())
            ->orderBy('name', 'ASC')->get();
    }

    public function module_chapter_based_question(Request $request)
    {

        $query = Chapter::where('status', 1);

        if ($request->has('module_id')) {
            $module = Module::where('id', request()->module_id)->first();
            $query->where('module_id', $module->id);
        }

        if ($request->has('chapter_id') && $request->get('chapter_id')) {
            $query->where('id', request()->chapter_id);
        }

        $used_questions = QuestionPaperQuestion::select('question_id')->groupBy('question_id')->get();
        $check_data = [];
        foreach ($used_questions as $item) {
            $check_data[] = $item->question_id;
        }

        $query->with([
            'module:id,name',
        ])
            ->withCount([
                'questions' => function ($q) {
                    $q->where('status', 1);
                },
                'used_question' => function ($q) use ($check_data) {
                    $q->where('status', 1)
                        ->whereIn('id', $check_data);
                },
                'intact_question' => function ($q) use ($check_data) {
                    $q->where('status', 1)
                        ->whereNotIn('id', $check_data);
                },
                'level_1' => function ($q) {
                    $q->where('status', 1)
                        ->where('level', 'Level 1');
                },
                'level_2' => function ($q) {
                    $q->where('status', 1)
                        ->where('level', 'Level 2');
                },
                'level_3' => function ($q) {
                    $q->where('status', 1)
                        ->where('level', 'Level 3');
                },
            ]);

        if (request()->has('per_page')) {
            $chapter = $query->paginate(request()->per_page);
        } else {
            $chapter = $query->paginate(env('PAGINATE'));
        }

        return response()->json($chapter);
    }

    public function all(Request $request)
    {
        $query = Question::where('status', 1)->orderBy('id', 'DESC')
            ->whereIn('module_id', $this->active_modules())
            ->whereIn('chapter_id', $this->active_chapters())
            ->with([
                'module' => function ($q) {
                    return $q->select(['id', 'name']);
                },
                'chapter' => function ($q) {
                    return $q->select(['id', 'chapter_name']);
                },
            ])
            ->withCount(['used_question']);

        if ($request->has('chapter_id') && (int)$request->chapter_id > 0) {
            $query->where('chapter_id', $request->chapter_id);
        }

        if ($request->has('type') && strlen($request->type) >= 3) {
            if ($request->type == 'used' || $request->type == 'intact') {
                $used_questions = QuestionPaperQuestion::select('question_id')->groupBy('question_id')->get();
                $check_data = [];
                foreach ($used_questions as $item) {
                    $check_data[] = $item->question_id;
                }
                if ($request->type == 'used') {
                    $query->whereIn('id', $check_data);
                }
                if ($request->type == 'intact') {
                    $query->whereNotIn('id', $check_data);
                }
            }

            if (count(explode('Level', $request->type)) > 1) {
                $query->where('level', $request->type);
            }
        }

        if ($request->has('module_id') && (int) $request->module_id > 0) {
            $query->where('module_id', $request->module_id);
        }

        if ($request->has('key') && strlen($request->key) > 0) {
            $key = $request->key;
            $query->where(function ($q) use ($key) {
                $q->where('question_title', $key)
                    ->orWhere('id', $key)
                    ->orWhere('question_title', 'LIKE', '%' . $key . '%')
                    ->orWhere('module_id', $key)
                    ->orWhere('chapter_id', $key)
                    ->orWhere('answer', 'LIKE', '%' . $key . '%')
                    ->orWhere('reference', 'LIKE', '%' . $key . '%')
                    ->orWhere('option_1', 'LIKE', '%' . $key . '%')
                    ->orWhere('option_2', 'LIKE', '%' . $key . '%')
                    ->orWhere('option_3', 'LIKE', '%' . $key . '%')
                    ->orWhere('level', 'LIKE', '%' . $key . '%')
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
        if ($request->has('per_page') && $request->per_page) {
            $data = $query->paginate($request->per_page);
        } else {
            $data = $query->paginate(env('PAGINATE'));
        }
        return response()->json($data);
    }

    public function get_used_details($id)
    {
        $question = Question::where('id', $id)
            ->select(['id', 'question_title'])
            ->with([
                'used_question' => function ($q) {
                    $q->select(['id', 'question_id', 'question_paper_id'])
                        ->with([
                            'question_paper' => function ($q) {
                                $q->withCount(['questions']);
                            }
                        ]);
                }
            ])
            ->first();

        return response()->json($question);
    }

    public function get(Question $question)
    {
        return response()->json($question);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'question_title' => ['required'],
            'module_id' => ['required'],
            'chapter_id' => ['required'],
            'question_pattern' => ['required'],

        ], [
            'module_id.required' => 'The module field is required',
        ]);

        if ($request->question_pattern == 'mcq') {
            $this->validate($request, [
                'answer' => ['required'],
                'reference' => ['required'],
                'option_1' => ['required'],
                'option_2' => ['required'],
                'option_3' => ['required'],
            ]);
        }

        $data = Question::create(request()->all());
        $data->creator = auth()->user()->id;
        $data->save();

        if (request()->hasFile('question_image')) {
            $data->question_image = Storage::put('uploads/question/' . str_replace(' ', '-', $data->question_title . $data->id), request()->file('question_image'));
        }
        if (request()->hasFile('option_1_image')) {
            $data->option_1_image = Storage::put('uploads/question/' . str_replace(' ', '-', $data->question_title . $data->id), request()->file('option_1_image'));
        }
        if (request()->hasFile('option_2_image')) {
            $data->option_2_image = Storage::put('uploads/question/' . str_replace(' ', '-', $data->question_title . $data->id), request()->file('option_2_image'));
        }
        if (request()->hasFile('option_3_image')) {
            $data->option_3_image = Storage::put('uploads/question/' . str_replace(' ', '-', $data->question_title . $data->id), request()->file('option_3_image'));
        }
        if (request()->hasFile('answer_image')) {
            $data->answer_image = Storage::put('uploads/question/' . str_replace(' ', '-', $data->question_title . $data->id), request()->file('answer_image'));
        }
        $data->save();

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'question_title' => ['required'],
            'module_id' => ['required'],
            'chapter_id' => ['required'],
            'question_pattern' => ['required'],

        ], [
            'module_id.required' => 'The module field is required',
        ]);

        if ($request->question_pattern == 'mcq') {
            $this->validate($request, [
                'answer' => ['required'],
                'reference' => ['required'],
                'option_1' => ['required'],
                'option_2' => ['required'],
                'option_3' => ['required'],
            ]);
        }

        $data = Question::find(request()->id);
        $data->fill(request()->all());
        $data->creator = auth()->user()->id;
        $data->save();

        if (request()->hasFile('question_image')) {
            $data->question_image = Storage::put('uploads/question/' . str_replace(' ', '-', $data->question_title . $data->id), request()->file('question_image'));
        }
        if (request()->hasFile('option_1_image')) {
            $data->option_1_image = Storage::put('uploads/question/' . str_replace(' ', '-', $data->question_title . $data->id), request()->file('option_1_image'));
        }
        if (request()->hasFile('option_2_image')) {
            $data->option_2_image = Storage::put('uploads/question/' . str_replace(' ', '-', $data->question_title . $data->id), request()->file('option_2_image'));
        }
        if (request()->hasFile('option_3_image')) {
            $data->option_3_image = Storage::put('uploads/question/' . str_replace(' ', '-', $data->question_title . $data->id), request()->file('option_3_image'));
        }
        if (request()->hasFile('answer_image')) {
            $data->answer_image = Storage::put('uploads/question/' . str_replace(' ', '-', $data->question_title . $data->id), request()->file('answer_image'));
        }
        $data->save();

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $data = Question::find($request->id);
        $data->status = 0;
        $data->save();
        // QuestionPaper::where('question_id',$data->id)->update(['status'=>0]);
        return 1;
        // return $this->all($request);
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
                            'question_pattern' => $data[2],
                            'question_title' => $data[3],
                            'option1' => $data[4],
                            'option2' => $data[5],
                            'option3' => $data[6],
                            'answer' => $data[7],
                            'reference' => $data[8],
                            'level' => $data[9],
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
        foreach (request()->data as $item) {
            $data = new Question();
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
            $data->question_pattern = $item['question_pattern'];
            $data->question_title = $item['question_title'];
            $data->option_1 = $item['option1'];
            $data->option_2 = $item['option2'];
            $data->option_3 = $item['option3'];
            $data->answer = $item['answer'];
            $data->reference = $item['reference'];
            $data->level = strlen($item['level']) > 4 ? $item['level'] : 'undefined';
            $data->creator = auth()->user()->id;
            $data->save();
        }
        return response()->json('data uploaded');
    }

    public function export_all(Request $request)
    {
        $fp = fopen(public_path('export.csv'), 'w');
        fputcsv($fp, [
            'module',
            'chapter',
            'question_pattern',
            'question_title',
            'option_1',
            'option_2',
            'option_3',
            'answer',
            'reference',
            'level',
        ]);
        $data = Question::where('status', 1)
            ->whereIn('module_id', $this->active_modules())
            ->whereIn('chapter_id', $this->active_chapters())
            ->with(['module', 'chapter'])
            ->get();
        foreach ($data as $question) {
            $fields = [
                $question->module->name,
                $question->chapter->chapter_name,
                $question->question_pattern,
                $question->question_title,
                $question->option_1,
                $question->option_2,
                $question->option_3,
                $question->answer,
                $question->reference,
                $question->level,
            ];
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    public function export_selected(Request $request)
    {
        $fp = fopen(public_path('export.csv'), 'w');
        fputcsv($fp, [
            'module',
            'chapter',
            'question_pattern',
            'question_title',
            'option_1',
            'option_2',
            'option_3',
            'answer',
            'reference',
            'level',
        ]);
        if (request()->has('get_data_by_id') && $request->get_data_by_id) {
            $data =  $this->get(Question::find($request->get_data_by_id))->getData();
            // dd($data->getData());
        } else {
            $data = (object) $request->data;
        }
        foreach ($data as $question) {
            $question = (object) $question;
            $question->module = (object) $question->module;
            $question->chapter = (object) $question->chapter;
            $fields = [
                $question->module->name,
                $question->chapter->chapter_name,
                $question->question_pattern,
                $question->question_title,
                $question->option_1,
                $question->option_2,
                $question->option_3,
                $question->answer,
                $question->reference,
                $question->level,
            ];
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    public function get_all_module_chapter_based_question($module_id = null, $chapter_id = null)
    {
        $query = Chapter::where('status', 1);

        if (!isNull($module_id)) {
            $module = Module::where('id', $module_id)->first();
            $query->where('module_id', $module->id);
        }

        if (!isNull($chapter_id)) {
            $query->where('id', $chapter_id);
        }

        $used_questions = QuestionPaperQuestion::select('question_id')->groupBy('question_id')->get();
        $check_data = [];
        foreach ($used_questions as $item) {
            $check_data[] = $item->question_id;
        }

        $query->with([
            'module:id,name',
        ])
            ->withCount([
                'questions' => function ($q) {
                    $q->where('status', 1);
                },
                'used_question' => function ($q) use ($check_data) {
                    $q->where('status', 1)
                        ->whereIn('id', $check_data);
                },
                'intact_question' => function ($q) use ($check_data) {
                    $q->where('status', 1)
                        ->whereNotIn('id', $check_data);
                },
                'level_1' => function ($q) {
                    $q->where('status', 1)
                        ->where('level', 'Level 1');
                },
                'level_2' => function ($q) {
                    $q->where('status', 1)
                        ->where('level', 'Level 2');
                },
                'level_3' => function ($q) {
                    $q->where('status', 1)
                        ->where('level', 'Level 3');
                },
            ]);

        $chapter = $query->get();
        return response()->json($chapter);
    }

    public function export_and_delete_selected(Request $request)
    {
        $fp = fopen(public_path('export.csv'), 'w');
        fputcsv($fp, [
            'module',
            'chapter',
            'question_pattern',
            'question_title',
            'option_1',
            'option_2',
            'option_3',
            'answer',
            'reference',
            'level',
        ]);
        if (request()->has('get_data_by_id') && $request->get_data_by_id) {
            $data =  $this->get(Question::find($request->get_data_by_id))->getData();
            // dd($data->getData());
        } else {
            $data = (object) $request->data;
        }
        foreach ($data as $question) {
            $question = (object) $question;
            $question->module = (object) $question->module;
            $question->chapter = (object) $question->chapter;
            Question::where('id', $question->id)->delete();
            $fields = [
                $question->module->name,
                $question->chapter->chapter_name,
                $question->question_pattern,
                $question->question_title,
                $question->option_1,
                $question->option_2,
                $question->option_3,
                $question->answer,
                $question->reference,
                $question->level,
            ];
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    public function export_all_module_based_selected(Request $request)
    {
        $fp = fopen(public_path('export.csv'), 'w');
        fputcsv($fp, [
            'module',
            'chapter',
            'total_question',
            'used',
            'intact',
            'level_1',
            'level_2',
            'level_3',
        ]);

        $data = (object) $request->data;

        foreach ($data as $item) {
            $item = (object) $item;
            $item->module = (object) $item->module;
            $fields = [
                $item->module->name,
                $item->chapter_name,
                $item->questions_count,
                $item->used_question_count,
                $item->intact_question_count,
                $item->level_1_count,
                $item->level_2_count,
                $item->level_3_count,
            ];
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    public function export_all_module_based(Request $request)
    {
        $fp = fopen(public_path('export.csv'), 'w');
        fputcsv($fp, [
            'module',
            'chapter',
            'total_question',
            'used',
            'intact',
            'level_1',
            'level_2',
            'level_3',
        ]);

        $data = $this->get_all_module_chapter_based_question($request)->getData();
        // dd($data);
        foreach ($data as $item) {
            $item = (object) $item;
            $item->module = (object) $item->module;
            $fields = [
                $item->module->name,
                $item->chapter_name,
                $item->questions_count,
                $item->used_question_count,
                $item->intact_question_count,
                $item->level_1_count,
                $item->level_2_count,
                $item->level_3_count,
            ];
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }
}