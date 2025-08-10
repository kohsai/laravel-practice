<?php
/*
📘 Step3 教材⑦：DB連動（マイグレーション／モデル／バリデーション）①〜③
対象ブランチ：day3-routing-edit-update（維持）
前提：Docker（nginx/php/mysql）起動／artisanはWSLホスト側から実行
配置：このファイルは「教材メモ」です（実行コードは src/laravel 配下に反映）

────────────────────────────────────
🟦 目的
・Route::resource で仮実装していた tasks をDB連動に切り替える下準備
・テーブル作成（Migration）／モデル作成（Eloquent）／最低限のバリデーション導入の入口

参考（出典）：
- Migrations：Laravel公式Docs … https://laravel.com/docs/12.x/migrations
- Eloquentモデル概要：Laravel公式Docs … https://laravel.com/docs/12.x/eloquent
- バリデーション（FormRequest含む）：Laravel公式Docs … https://laravel.com/docs/12.x/validation

────────────────────────────────────
🟩 ① モデル＆マイグレーション作成
（作業ディレクトリ：~/venpro/laravel-practice/src/laravel）
# bash
cd ~/venpro/laravel-practice/src/laravel
php artisan make:model Task -m

生成物（相対パス）：
- app/Models/Task.php
- database/migrations/xxxx_xx_xx_xxxxxx_create_tasks_table.php

モデルの推奨追記（mass assignment 対策として fillable を定義）
# app/Models/Task.php（抜粋・例）
class Task extends Model {
    use HasFactory;
    protected $fillable = ['title', 'description'];
}
※ fillable の考え方（参考：Eloquentの一括代入の保護）。出典：Eloquent概要（上記URL）

────────────────────────────────────
🟩 ② マイグレーション編集
# database/migrations/*create_tasks_table.php（全体例）
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');                 // 必須：タイトル
            $table->text('description')->nullable(); // 任意：説明
            $table->timestamps();                    // created_at / updated_at
        });
    }
    public function down(): void {
        Schema::dropIfExists('tasks');
    }
};

ポイント：
・up()/down() で作成とロールバックを対に定義（出典：Migrationsの構造）

────────────────────────────────────
🟩 ③ マイグレーション実行
# bash（src/laravel 直下）
php artisan migrate

想定コンソール出力例：
Migrating: 2025_08_10_123456_create_tasks_table
Migrated:  2025_08_10_123456_create_tasks_table (xxx ms)

DB確認（phpMyAdmin）：
1) http://localhost:8081 を開く
2) データベース「laravel_db」
3) テーブル「tasks」を確認（カラム：id, title, description, created_at, updated_at）

────────────────────────────────────
🔎 トラブルシュート
・接続拒否（HY000/2002 など）：`docker-compose ps` で mysql 稼働確認
・.env の整合（Docker構成と一致）
  DB_HOST=mysql / DB_DATABASE=laravel_db / DB_USERNAME=laravel_user / DB_PASSWORD=secret
・設定キャッシュ：`php artisan config:clear`
・状態確認：`php artisan migrate:status`

────────────────────────────────────
📌 次段（この後の流れの予告）
・フォーム入力 → Requestバリデーション → Task保存／更新 へ進みます
・バリデーションはまずコントローラ内の `$request->validate([...])` から開始し、
  後で FormRequest への分離（`php artisan make:request StoreTaskRequest`）を行います（出典：Validation）。
*/
