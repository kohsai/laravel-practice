# Day3: RESTfulルーティングとTask CRUD機能の実装

## 📌 実施内容

- Route::resource による7アクションの自動定義
- TaskController の各アクション実装（index / create / store / show / edit / update / destroy）
- Bladeビュー作成（index.blade.php / create.blade.php / edit.blade.php / show.blade.php）
- Task の CRUD操作を一貫して構築（フォーム連携含む）
- `$request->validate()` を TaskRequest に分離（FormRequest）
- バリデーションエラーメッセージの日本語化（resources/lang/ja/validation.php）
- `$id` → `Task $task` へのルートモデルバインディングを導入
- Laravelの「暗黙のバインディング」により findOrFail() を排除

## 📁 対象ファイル

- routes/web.php
- app/Http/Controllers/TaskController.php
- app/Http/Requests/TaskRequest.php
- app/Models/Task.php
- resources/views/tasks/index.blade.php
- resources/views/tasks/create.blade.php
- resources/views/tasks/edit.blade.php
- resources/views/tasks/show.blade.php
- resources/views/layouts/app.blade.php
- resources/lang/ja/validation.php
- database/migrations/****_create_tasks_table.php

## 🔗 GitHubリンク

- [day3-routing-restful ブランチ（GitHub）](https://github.com/kohsai/laravel-practice/tree/day3-routing-restful)
- [day3-routing-edit-update ブランチ（GitHub）](https://github.com/kohsai/laravel-practice/tree/day3-routing-edit-update)
