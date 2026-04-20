<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    // 多対多リレーション：このタグを持つユーザーたち
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // 多対多リレーション：このタグがついている支出たち
    public function expenses()
    {
        return $this->belongsToMany(Expense::class);
    }
}
