<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TokenKelas;
use App\Models\ClassEnrollment;

class Classes extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'id_class';

    protected $fillable = [
        'nama_kelas',
        'deskripsi',
        'created_by',
        'max_students',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Guru pembuat kelas
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    // Token join kelas
    public function tokens()
    {
        return $this->hasMany(TokenKelas::class, 'id_class', 'id_class');
    }

    public function activeToken()
    {
        return $this->hasOne(TokenKelas::class, 'id_class', 'id_class')->latestOfMany('id_token');
    }

    // Cek apakah token aktif masih valid
    public function hasValidToken(): bool
    {
        $token = $this->activeToken()->first();
        if (!$token) {
            return false;
        }
        return $token->isValid();
    }

    // Dapatkan status token aktif
    public function getTokenStatus(): string
    {
        $token = $this->activeToken()->first();
        if (!$token) {
            return 'Tidak ada token';
        }
        if ($token->expires_at && now()->greaterThan($token->expires_at)) {
            return 'Kadaluarsa';
        }
        if ($token->max_uses > 0 && $token->times_used >= $token->max_uses) {
            return 'Batas penggunaan tercapai';
        }
        return 'Aktif';
    }

    // Siswa yang ikut kelas
    public function enrollments()
    {
        return $this->hasMany(ClassEnrollment::class, 'id_class', 'id_class');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'id_class', 'id_class');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'id_class', 'id_class');
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'id_class', 'id_class');
    }
}
