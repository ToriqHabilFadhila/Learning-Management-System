<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassEnrollment extends Model
{
    protected $table = 'class_enrollments';
    protected $primaryKey = 'id_enrollment';
    public $timestamps = false;

    protected $fillable = [
        'id_class',
        'id_user',
        'status',
        'token_used',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'id_class', 'id_class');
    }
}
