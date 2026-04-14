<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'assignments';
    protected $primaryKey = 'id_assignment';
    
    protected $fillable = [
        'id_class',
        'judul',
        'deskripsi',
        'tipe',
        'deadline',
        'max_score',
        'created_by',
        'is_published',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'id_class', 'id_class');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'id_assignment', 'id_assignment')->orderBy('urutan');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'id_assignment', 'id_assignment');
    }
}
