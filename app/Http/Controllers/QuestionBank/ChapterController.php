<?php

namespace App\Http\Controllers\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuestionPaper;
use App\Models\QuestionPaperQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChapterController extends Controller
{
    public function get_all()
    {
        $query = Chapter::select(['id', 'chapter_name'])
            ->whereIn('module_id', $this->active_modules())
            ->where('status', 1)
            ->orderBy('id', 'ASC');
        if (request()->has('module_id') && strlen(request()->module_id)) {
            $query->where('module_id', request()->module_id);
        }
        return $query->get();
    }

    public function active_modules()
    {
        $active_modules = Module::select('id', 'status')->where('status', 1)->get();
        $data = [];
        foreach ($active_modules as $item) {
            $data[] = $item->id;
        }
        return $data;
    }

    public function all(Request $request)
    {

        $query = Chapter::where('status', 1)->orderBy('id', 'ASC')
            ->with([
                'module' => function ($q) {
                    return $q->where('status', 1)->select(['id', 'name']);
                }
            ])
            ->whereIn('module_id', $this->active_modules());

        if ($request->has('key') && strlen($request->key) > 0) {
            $key = $request->key;
            $query->where(function ($q) use ($key) {
                $q->where('chapter_name', $key)
                    ->orWhere('id', $key)
                    ->orWhere('chapter_name', 'LIKE', '%' . $key . '%')
                    ->orWhere('module_id', $key);
            });
        }
        // $data = $query->paginate(env('PAGINATE'));
        $data = $query->paginate(50);
        return response()->json($data);
    }

    public function get(Chapter $chapter)
    {
        if (request()->has('type') && request()->type == 'with_details') {
            $used_questions = QuestionPaperQuestion::select('question_id')->groupBy('question_id')->get();
            $check_data = [];
            foreach ($used_questions as $item) {
                $check_data[] = $item->question_id;
            }
            $chapter->total_question = $chapter->questions()->where('status', 1)->count();
            $chapter->used = $chapter->questions()->where('status', 1)->whereIn('id', $check_data)->count();
            $chapter->intact = $chapter->questions()->where('status', 1)->whereNotIn('id', $check_data)->count();
            $chapter->level_1 = $chapter->questions()->where('status', 1)->where('level', 'level_1')->count();
            $chapter->level_2 = $chapter->questions()->where('status', 1)->where('level', 'level_2')->count();
            $chapter->level_3 = $chapter->questions()->where('status', 1)->where('level', 'level_3')->count();
        }
        return response()->json($chapter);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'chapter_name' => ['required'],
            'module_id' => ['required'],
        ], [
            'module_id.required' => 'The module field is required',
        ]);

        $data = Chapter::create(request()->all());
        $data->creator = auth()->user()->id;
        $data->save();
        return response()->json($data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'chapter_name' => ['required'],
            'module_id' => ['required'],
        ], [
            'module_id.required' => 'The module  field is required',
        ]);

        $data = Chapter::find(request()->id);
        $data->fill(request()->all());
        $data->creator = auth()->user()->id;
        $data->save();
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $data = Chapter::find($request->id);
        $data->status = 0;
        $data->save();
        Question::where('chapter_id', $data->id)->update(['status' => 0]);
        QuestionPaper::where('chapter_id', $data->id)->update(['status' => 0]);
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
            $data = new Chapter();
            if (Module::where('name', 'LIKE', '%' . $item['module'] . '%')->exists()) {
                $data->module_id = Module::where('name', 'LIKE', '%' . $item['module'] . '%')->first()->id;
            } else {
                $module = Module::create([
                    'name' => $item['module'],
                    'creator' => auth()->user()->id,
                ]);
                $data->module_id = $module->id;
            }
            $data->chapter_name = $item['chapter'];
            $data->creator = auth()->user()->id;
            $data->save();
        }
        return response()->json('data uploaded');
    }

    public function export_all(Request $request)
    {
        $fp = fopen(public_path('export.csv'), 'w');
        fputcsv($fp, [
            'Module',
            'Chapter',
        ]);
        $data = Chapter::where('status', 1)
            ->whereIn('module_id', $this->active_modules())
            ->with('module')
            ->get();
        foreach ($data as $item) {
            $fields = [
                $item->module->name,
                $item->chapter_name,
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
            $item->module = (object) $item->module;
            $fields = [
                $item->module->name,
                $item->chapter_name,
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
            'Chapter',
        ]);
        $data = (object) $request->data;

        foreach ($data as $item) {
            $item = (object) $item;
            $item->module = (object) $item->module;
            Chapter::where('id', $item->id)->update(['status' => 0]);
            $fields = [
                $item->module->name,
                $item->chapter_name,
            ];
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }
}
