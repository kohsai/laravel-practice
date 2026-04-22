<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    
    //      このロールを持つユーザーを取得する
    //      多対多（たたいた）リレーション
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
