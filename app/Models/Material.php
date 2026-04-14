<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id_material';
    
    protected $fillable = [
        'id_class',
        'judul',
        'konten',
        'file_path',
        'file_type',
        'online_link',
        'uploaded_by',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'id_class', 'id_class');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id_user');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id_user');
    }
}
