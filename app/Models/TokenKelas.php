<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Classes;

class TokenKelas extends Model
{
    protected $table = 'token_kelas';
    protected $primaryKey = 'id_token';

    public $timestamps = false;

    protected $fillable = [
        'id_class',
        'token_code',
        'created_by',
        'expires_at',
        'max_uses',
        'times_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Token milik kelas tertentu
    public function kelas()
    {
        return $this->belongsTo(Classes::class, 'id_class', 'id_class');
    }

    // Guru pembuat token
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    // Cek apakah token masih bisa dipakai
    public function isValid(): bool
    {
        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return false;
        }

        if ($this->max_uses > 0 && $this->times_used >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function getStatusAttribute(): string
    {
        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return 'expired';
        }
        if ($this->max_uses > 0 && $this->times_used >= $this->max_uses) {
            return 'max_uses_reached';
        }
        return 'active';
    }
}
