<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submissions';
    protected $primaryKey = 'id_submission';
    public $timestamps = false;
    
    protected $fillable = [
        'id_assignment',
        'id_user',
        'jawaban',
        'file_path',
        'submitted_at',
        'score',
        'status',
        'graded_by',
        'graded_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'id_assignment', 'id_assignment');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by', 'id_user');
    }

    public function feedback()
    {
        return $this->hasOne(FeedbackAI::class, 'id_submission', 'id_submission');
    }
}
