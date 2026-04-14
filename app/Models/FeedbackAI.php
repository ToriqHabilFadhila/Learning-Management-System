<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackAI extends Model
{
    protected $table = 'feedback_ai';
    protected $primaryKey = 'id_feedback';
    public $timestamps = false;

    protected $fillable = [
        'id_submission',
        'feedback_text',
        'saran',
        'question',
        'answer',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class, 'id_submission', 'id_submission');
    }
}
