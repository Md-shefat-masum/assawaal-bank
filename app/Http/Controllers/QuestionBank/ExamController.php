<?php

namespace App\Http\Controllers\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestionPaper;
use App\Models\QuestionPaper;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function get_all()
    {
        $query = Exam::select(['id', 'name'])->where('status', 1)->orderBy('name', 'ASC');
        if (request()->has('module_id') && strlen(request()->module_id)) {
            $query->where('module_id', request()->module_id);
        }
        return $query->get();
    }

    public function all(Request $request)
    {
        $query = Exam::where('status', 1)->orderBy('id', 'DESC')
            // ->with([
            //     'question_paper' => function ($q) {
            //         return $q->select(['id', 'name']);
            //     }
            // ])
            ->withCount(['question_paper']);

        if ($request->has('key') && strlen($request->key) > 0) {
            $key = $request->key;
            $query->where(function ($q) use ($key) {
                $q->where('name', $key)
                    ->orWhere('id', $key)
                    ->orWhere('name', 'LIKE', '%' . $key . '%')
                    ->orWhere('class', 'LIKE', '%' . $key . '%')
                    ->orWhere('session', 'LIKE', '%' . $key . '%');
            });
        }
        $data = $query->paginate(env('PAGINATE'));
        return response()->json($data);
    }

    public function get(Exam $exam)
    {
        $exam->question_paper = $exam->question_paper()
            ->with([
                'question_paper_details' => function ($q) {
                    return $q->withCount(['questions']);
                }
            ])->get();
        $selected_question_list = [];
        $selected_question = [];
        foreach ($exam->question_paper as $question_paper) {
            if($question_paper->question_paper_details){
                $selected_question_list[] = $question_paper->question_paper_details;
                $selected_question[] = $question_paper->question_paper_details->id;
            }
        }
        $exam->selected_question_list = $selected_question_list;
        $exam->selected_question = $selected_question;
        return response()->json($exam);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'class' => ['required'],
            'session' => ['required'],
            'selected_question' => ['min:3', 'required'],
        ], [
            'selected_question.min' => 'There is no question paper is selected',
        ]);

        $data = Exam::create(request()->except('selected_question'));
        $data->creator = auth()->user()->id;
        $data->save();

        foreach (json_decode(request()->selected_question) as $question_paper_id) {
            ExamQuestionPaper::create([
                'exam_id' => $data->id,
                'question_paper_id' => $question_paper_id,
            ]);
        }
        return response()->json($data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'class' => ['required'],
            'session' => ['required'],
            'selected_question' => ['min:3', 'required'],
        ], [
            'selected_question.min' => 'There is no question paper is selected',
        ]);

        $data = Exam::find(request()->id);
        $data->fill(request()->except('selected_question'));
        $data->creator = auth()->user()->id;
        $data->save();

        ExamQuestionPaper::where('exam_id', $data->id)->delete();
        foreach (json_decode(request()->selected_question) as $question_paper_id) {
            ExamQuestionPaper::create([
                'exam_id' => $data->id,
                'question_paper_id' => $question_paper_id,
            ]);
        }

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $data = Exam::find($request->id);
        $data->status = 0;
        $data->save();
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
                            'exam_name' => $data[0],
                            'class' => $data[1],
                            'session' => $data[2],
                            'question_paper_name' => $data[3],
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
        $exam_names = $datas->unique('exam_name');

        foreach ($exam_names as $item) {
            $data = new Exam();
            $data->name = $item['exam_name'];
            $data->class = $item['class'];
            $data->session = $item['session'];
            $data->creator = auth()->user()->id;
            $data->save();

            $question_papers = $datas->where('exam_name', $item['exam_name'])->all();
            foreach ($question_papers as $question_paper) {
                if(QuestionPaper::where('name','LIKE','%'.$question_paper['question_paper_name'])->exists()){
                    $question_paper = QuestionPaper::where('name','LIKE','%'.$question_paper['question_paper_name'])->first();
                    ExamQuestionPaper::create([
                        'exam_id' => $data->id,
                        'question_paper_id' => $question_paper->id,
                    ]);
                }
            }
        }
        return response()->json('data uploaded');
    }
}
