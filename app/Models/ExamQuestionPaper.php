<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestionPaper extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function question_paper_details()
    {
        return $this->belongsTo(QuestionPaper::class,'question_paper_id');
    }
}
