<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
/** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $primaryKey = 'id_user';
    protected $appends = ['avatar_url'];
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'is_active',
        'is_verified',
        'last_login',
        'profile_picture',
        'email_verified_at',
        'fcm_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'is_verified' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke kelas yang diikuti user (untuk siswa)
     */
    public function enrollments()
    {
        return $this->hasMany(\App\Models\ClassEnrollment::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke kelas yang dibuat user (untuk guru)
     */
    public function createdClasses()
    {
        return $this->hasMany(\App\Models\Classes::class, 'created_by', 'id_user');
    }

    /**
     * Relasi ke activity logs
     */
    public function activityLogs()
    {
        return $this->hasMany(\App\Models\ActivityLog::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke progress
     */
    public function progress()
    {
        return $this->hasMany(\App\Models\Progress::class, 'id_user', 'id_user');
    }

    /**
     * Scope untuk users yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get avatar URL
     */

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->profile_picture) return null;

                // External Google avatar (URL lengkap)
                if (str_starts_with($this->profile_picture, 'http')) {
                    return $this->profile_picture;
                }

                // Local storage file
                $localPath = storage_path('app/public/avatars/' . $this->profile_picture);
                if (file_exists($localPath)) {
                    return asset('storage/avatars/' . $this->profile_picture);
                }

                return null;
            }
        );
    }

    /**
     * Route notification for FCM
     */
    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
