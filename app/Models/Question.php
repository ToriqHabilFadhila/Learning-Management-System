<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'id_question';
    
    protected $fillable = [
        'id_assignment',
        'soal',
        'kunci_jawaban',
        'poin',
        'urutan',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'id_assignment', 'id_assignment');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'id_question', 'id_question');
    }
}
