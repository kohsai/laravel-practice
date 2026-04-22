<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Role;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'password' => 'hashed',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // 多対多リレーション：このユーザーが持つタグたち
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * このユーザーが持つロールを取得する
     * 多対多（たたいた）リレーション
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * 指定したロールを持っているか確認する
     *
     * 使い方：$user->hasRole('admin')
     * → admin ロールを持っていれば true、なければ false
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }
}
