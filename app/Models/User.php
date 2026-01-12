<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $appends = ['status_label'];
    protected $status_labels = [1 => "Active", 2 => "Inactive", 3 => "Block"];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'mobile',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return $this->status_labels[$this->status];
    }

    public function roles()
    {
        // Basic pivot table relationship
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')->withTimestamps(); // includes created_at and updated_at
    }

    public function societies()
    {
        // Basic pivot table relationship
        return $this->belongsToMany(Society::class, 'user_roles', 'user_id', 'society_id')->withTimestamps(); // includes created_at and updated_at
    }
}
