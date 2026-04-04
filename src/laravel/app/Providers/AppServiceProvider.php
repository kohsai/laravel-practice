<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 管理者かどうかを判定するGate
        // 'admin-only' = このGateの名前
        // $user->is_admin = usersテーブルのis_adminカラム（1なら管理者）
        Gate::define('admin-only', function ($user) {
            return $user->is_admin === 1;
        });

        // 投稿の作成者かどうかを判定するGate
        // $user = ログイン中のユーザー
        // $post = 判定したい投稿
        Gate::define('update-post', function ($user, $post) {
            return $user->id === $post->user_id;
        });
    }
}
