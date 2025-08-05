<?php
// 📘 Step3 教材④：Bladeビューとの連携（resource_blade_views.php）

// ✅ この教材の目的：
// TaskController の各アクションメソッドから仮の return 文（文字列）を廃止し、
// 実際に resources/views 配下の Blade ビューを返すように変更する。
// また、layouts/app.blade.php による共通レイアウト適用の実践を含む。

// ------------------------------------------------------------
// 🔧 事前準備（ビュー用ディレクトリの作成）
// 以下のコマンドで Blade ファイルを保存するディレクトリを作成する：
// mkdir -p src/laravel/resources/views/tasks

// ------------------------------------------------------------
// 🧱 1. TaskController の各メソッドに対応するビューを返す処理へ変更する

// 🟢 TaskController.php にて以下のように return view(...) を記述する：
// ※ 書き換え対象：index(), create(), show($id), edit($id)

// ▼ 一覧表示
/*
public function index()
{
    return view('tasks.index');
}
*/

// ▼ 作成フォーム表示
/*
public function create()
{
    return view('tasks.create');
}
*/

// ▼ 個別表示
/*
public function show(string $id)
{
    return view('tasks.show', ['id' => $id]);
}
*/

// ▼ 編集フォーム表示
/*
public function edit(string $id)
{
    return view('tasks.edit', ['id' => $id]);
}
*/

// ------------------------------------------------------------
// 📝 2. ビューファイルの作成（layouts/app.blade.php を共通レイアウトとして使用）
// ※ 以下すべて src/laravel/resources/views/tasks/ に作成すること

// tasks/index.blade.php
/*
@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
    <h2>タスク一覧</h2>
    <p>ここにタスク一覧を表示します（仮）</p>
@endsection
*/

// tasks/create.blade.php
/*
@extends('layouts.app')

@section('title', 'タスク作成')

@section('content')
    <h2>タスク作成フォーム</h2>
    <p>ここにタスク作成フォームを設置します（仮）</p>
@endsection
*/

// tasks/show.blade.php
/*
@extends('layouts.app')

@section('title', 'タスク詳細')

@section('content')
    <h2>タスク詳細</h2>
    <p>ID: {{ $id }} のタスクを表示しています（仮）</p>
@endsection
*/

// tasks/edit.blade.php
/*
@extends('layouts.app')

@section('title', 'タスク編集')

@section('content')
    <h2>タスク編集フォーム</h2>
    <p>ID: {{ $id }} のタスクを編集します（仮）</p>
@endsection
*/

// ------------------------------------------------------------
// 🔍 確認URL（Route::resource で自動生成されているため）
// GET /tasks            → 一覧（index）
// GET /tasks/create     → 作成フォーム（create）
// GET /tasks/{id}       → 個別表示（show）
// GET /tasks/{id}/edit  → 編集フォーム（edit）

// ------------------------------------------------------------
// ✅ 次のステップ
// - 上記 Blade ファイルを作成
// - Laravelをブラウザでアクセスし、各ビューが表示されるか確認
// - 表示が確認できたら → Gitへの記録 & 次の教材へ進む
