<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function module()
    {
        return $this->belongsTo(Module::class,'module_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class,'chapter_id');
    }
    public function used_question()
    {
        return $this->hasMany(Question::class,'chapter_id');
    }
    public function intact_question()
    {
        return $this->hasMany(Question::class,'chapter_id');
    }
    public function level_1()
    {
        return $this->hasMany(Question::class,'chapter_id');
    }
    public function level_2()
    {
        return $this->hasMany(Question::class,'chapter_id');
    }
    public function level_3()
    {
        return $this->hasMany(Question::class,'chapter_id');
    }
}
