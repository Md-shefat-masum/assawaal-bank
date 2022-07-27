<?php

namespace App\Http\Controllers\QuestionBank;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuestionPaperQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionBankController extends Controller
{
    public function at_a_glance(Request $request)
    {
        $used_questions = QuestionPaperQuestion::select('question_id')->groupBy('question_id')->get();
        $check_data =[];
        foreach ($used_questions as $item) {
            $check_data[] = $item->question_id;
        }
        // dd($check_data);
        $data = [
            'total_module' => Module::where('status', 1)->count(),
            'total_chapter' => Chapter::where('status', 1)->count(),
            'total_question' => Question::where('status', 1)->count(),
            'used_question' => Question::where('status', 1)->whereIn('id',$check_data)->count(),
            'intact_question' => Question::where('status', 1)->whereNotIn('id',$check_data)->count(),
            'level_1_question' => Question::where('status', 1)->where('level','Level 1')->count(),
            'level_2_question' => Question::where('status', 1)->where('level','Level 2')->count(),
            'level_3_question' => Question::where('status', 1)->where('level','Level 3')->count(),
        ];

        return response()->json($data);
    }
}
