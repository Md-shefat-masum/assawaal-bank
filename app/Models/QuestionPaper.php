<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionPaper extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function module()
    {
        return $this->hasOne(Module::class, 'id', 'module_id');
    }
    public function chapter()
    {
        return $this->hasOne(Chapter::class, 'id', 'chapter_id');
    }
    public function questions()
    {
        return $this->hasMany(QuestionPaperQuestion::class, 'question_paper_id');
    }
}
