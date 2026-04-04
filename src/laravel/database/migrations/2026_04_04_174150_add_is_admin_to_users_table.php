<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // is_admin（イズアドミン）カラムを追加
            // boolean（ブーリアン）= 1か0の2択（1=管理者、0=一般ユーザー）
            // default(0)（デフォルト）= 初期値は0（管理者ではない）
            // after('email')（アフター）= emailカラムの直後に追加
            $table->boolean('is_admin')->default(0)->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // マイグレーションを元に戻すときにis_adminカラムを削除する
            $table->dropColumn('is_admin');
        });
    }
};
