<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'action_type',
        'target_type',
        'target_id',
        'description',
        'ip_address',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
