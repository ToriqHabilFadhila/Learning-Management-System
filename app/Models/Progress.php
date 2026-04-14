<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $table = 'progress';
    protected $primaryKey = 'id_progress';

    protected $fillable = [
        'id_user',
        'id_class',
        'persentase',
        'status',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'id_class', 'id_class');
    }

    public function getProgressStatusAttribute()
    {
        if ($this->persentase >= 90) {
            return 'Excellent';
        } elseif ($this->persentase >= 75) {
            return 'Good';
        } elseif ($this->persentase >= 60) {
            return 'Fair';
        } else {
            return 'Needs Improvement';
        }
    }
}
