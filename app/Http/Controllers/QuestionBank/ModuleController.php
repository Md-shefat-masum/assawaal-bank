<?php

namespace App\Http\Controllers\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuestionPaper;
use App\Models\QuestionPaperQuestion;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function get_all()
    {
        return Module::select(['id', 'name'])->where('status', 1)->orderBy('id', 'ASC')->get();
    }

    public function all(Request $request)
    {
        $query = Module::where('status', 1)->orderBy('id', 'ASC');
        if ($request->has('key') && strlen($request->key) > 0) {
            $key = $request->key;
            $query->where(function ($q) use ($key) {
                $q->where('name', $key)
                    ->orWhere('id', $key)
                    ->orWhere('name', 'LIKE', '%' . $key . '%')
                    ->orWhere('number', $key);
            });
        }
        // $modules = $query->paginate(env('PAGINATE'));
        $modules = $query->paginate(100);
        return response()->json($modules);
    }

    public function get(Module $module)
    {
        if (request()->has('type') && request()->type == 'with_details') {
            $used_questions = QuestionPaperQuestion::select('question_id')->groupBy('question_id')->get();
            $check_data = [];
            foreach ($used_questions as $item) {
                $check_data[] = $item->question_id;
            }
            $module->total_question = $module->questions()->where('status', 1)->count();
            $module->used = $module->questions()->where('status', 1)->whereIn('id',$check_data)->count();
            $module->intact = $module->questions()->where('status', 1)->whereNotIn('id',$check_data)->count();
            $module->level_1 = $module->questions()->where('status', 1)->where('level','Level 1')->count();
            $module->level_2 = $module->questions()->where('status', 1)->where('level','Level 2')->count();
            $module->level_3 = $module->questions()->where('status', 1)->where('level','Level 3')->count();
        }
        return response()->json($module);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'number' => ['required'],
        ], [
            'number.required' => 'The module number field is required',
        ]);

        $module = Module::create(request()->all());
        $module->creator = auth()->user()->id;
        $module->save();
        return response()->json($module);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'number' => ['required'],
        ], [
            'number.required' => 'The module number field is required',
        ]);

        $module = Module::find(request()->id);
        $module->fill(request()->all());
        $module->creator = auth()->user()->id;
        $module->save();
        return response()->json($module);
    }

    public function delete(Request $request)
    {
        $module = Module::find($request->id);
        $module->status = 0;
        $module->save();
        Chapter::where('module_id', $module->id)->update(['status' => 0]);
        Question::where('module_id', $module->id)->update(['status' => 0]);
        QuestionPaper::where('module_id', $module->id)->update(['status' => 0]);
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
                        $modules[] = [
                            'name' => $data[0],
                            'number' => $data[1],
                        ];
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
            $module = Module::create($item);
            $module->creator = auth()->user()->id;
            $module->save();
        }
        return response()->json('data uploaded');
    }

    public function export_all(Request $request)
    {
        $fp = fopen(public_path('export.csv'), 'w');
        fputcsv($fp, [
            'Module',
            'Number',
        ]);
        $data = Module::where('status',1)->get();
        foreach ($data as $item) {
            $fields = [
                $item->name,
                $item->number,
            ];
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    public function export_selected(Request $request)
    {
        $fp = fopen(public_path('export.csv'), 'w');
        fputcsv($fp, [
            'Module',
            'Number',
        ]);
        $data = (object) $request->data;
        foreach ($data as $item) {
            $item = (object) $item;
            $fields = [
                $item->name,
                $item->number,
            ];
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    public function export_and_delete_selected(Request $request)
    {
        $fp = fopen(public_path('export.csv'), 'w');
        fputcsv($fp, [
            'Module',
            'Number',
        ]);
        $data = (object) $request->data;

        foreach ($data as $item) {
            $item = (object) $item;
            Module::where('id', $item->id)->update(['status'=>0]);
            $fields = [
                $item->name,
                $item->number,
            ];
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }
}
