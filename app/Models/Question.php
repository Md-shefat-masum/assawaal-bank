<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function module()
    {
        return $this->belongsTo(Module::class,'module_id');
    }
    public function chapter()
    {
        return $this->belongsTo(Chapter::class,'chapter_id');
    }
    public function used_question()
    {
        return $this->hasMany(QuestionPaperQuestion::class,'question_id');
    }
}
