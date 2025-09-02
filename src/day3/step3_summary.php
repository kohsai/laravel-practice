<?php

/**
 * 📘 Step3 Summary: Task CRUD 機能の実装（Day3）
 *
 * ■ 学習目標
 * - Laravelのリソースルーティング（7アクション）を習得する
 * - TaskControllerによるCRUD機能を一貫して構築する
 * - Bladeを使ったビュー表示とフォーム送信を連携させる
 * - バリデーションをFormRequest（TaskRequest）で共通化する
 * - 日本語バリデーションメッセージを導入しUXを改善する
 * - 暗黙のルートモデルバインディングを導入して簡潔で安全なコードにする
 *
 * ■ 主な実装ファイル
 * - routes/web.php
 * - app/Http/Controllers/TaskController.php
 * - app/Http/Requests/TaskRequest.php
 * - app/Models/Task.php
 * - resources/views/tasks/*.blade.php
 * - resources/lang/ja/validation.php
 * - database/migrations/xxxx_xx_xx_create_tasks_table.php
 *
 * ■ 技術ポイント
 * - Route::resource() により、7つのルーティングが自動定義される
 * - Bladeテンプレートでは、@extends / @section / @yield を用いたレイアウト構成を活用
 * - フォーム送信では @csrf / @method ディレクティブが必須
 * - TaskRequest によってバリデーションを外部クラスに分離・共通化
 * - `$id` → `Task $task` への書き換えにより、findOrFail() を使わずにインスタンス注入が可能になる
 *
 * ■ 最終ブランチ（mainにマージ済）
 * - day3-routing-restful
 * - day3-routing-edit-update
 */
