<?php

namespace App\Models;

use App\Models\Purchase;
use App\Models\AdminProfile;
use Laravel\Sanctum\HasApiTokens;
use Alauddin\Authorize\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Alauddin\Authorize\Models\Authorizable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Authorizable;

    const SUPER_ADMIN  = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(AdminProfile::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }


    public function is_online()
    {
        return Cache::has('user-is-online' . $this->id);
    }


    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function isAdmin()
    {
        return $this->role_id === self::SUPER_ADMIN;
    }

}
