<?php
/*
|--------------------------------------------------------------------------
| Step3 教材⑤：RESTful操作とBladeの実践的つなぎ込み（create → store）
|--------------------------------------------------------------------------
| この教材では、TaskControllerの create() / store() アクションに対応する
| Bladeテンプレートファイルを作成し、フォームからの送信と保存の流れを実装します。
|
| ▶︎ ファイル構成（該当部分のみ抜粋）：
|   - routes/web.php                       : Route::resource('tasks', TaskController::class);
|   - app/Http/Controllers/TaskController.php : store() メソッドを編集
|   - resources/views/tasks/create.blade.php  : 登録フォームを記述（仮表示 → 本実装に変更）
|
| ※ 今回はデータベース接続やバリデーション処理は行いません。
|    「フォーム送信とBladeの連携」部分の理解に集中します。
*/

/*
|--------------------------------------------------------------------------
| ✅ 1. create() メソッドはすでに実装済みのため再掲しません。
|--------------------------------------------------------------------------
| 対応ビュー：tasks/create.blade.php
*/

/*
|--------------------------------------------------------------------------
| ✅ 2. create.blade.php の内容を「仮表示 → フォーム実装」に更新
|--------------------------------------------------------------------------
| ファイルパス：resources/views/tasks/create.blade.php
| すでにある仮表示を、以下のフォームに置き換えてください。
*/

# --- 修正後の create.blade.php の内容（すべてPHPコメント） ---
/*
@extends('layouts.app')

@section('title', '新しいタスクの作成')

@section('content')
    <h2>新しいタスクを作成</h2>

    <!-- フォーム開始（POST /tasks に送信） -->
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf <!-- CSRFトークンを自動生成 -->

        <label for="title">タイトル:</label><br>
        <input type="text" name="title" id="title"><br><br>

        <label for="description">詳細:</label><br>
        <textarea name="description" id="description"></textarea><br><br>

        <button type="submit">保存</button>
    </form>
@endsection
*/

/*
|--------------------------------------------------------------------------
| ✅ 3. store() メソッドをフォーム連携用に実装
|--------------------------------------------------------------------------
| 以下のコードを TaskController.php に記述します。
| ※ store() は TaskController クラスの中に必ず配置してください。
*/

    /*
    // 保存処理（POST /tasks）
    public function store(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');

        return "タイトル：{$title} <br> 詳細：{$description}";
    }
    */

/*
|--------------------------------------------------------------------------
| 🔄 動作確認方法（手順）
|--------------------------------------------------------------------------
| 1. ブラウザで `/tasks/create` にアクセス
|    → フォームが表示されることを確認
|
| 2. 任意のタイトル・詳細を入力して送信
|    → `store()` メソッドで内容が表示されることを確認
*/

/*
|--------------------------------------------------------------------------
| 🧠 学習ポイントまとめ
|--------------------------------------------------------------------------
| ◆ Bladeでのフォーム構文：
|     @csrf を必ず入れる。action には route() ヘルパーを使う。
|
| ◆ 送信データの取得：
|     $request->input('name属性') で取得。
|
| ◆ create() の戻り値：
|     view('tasks.create') でBladeファイルを返す。
|
| ◆ store() の戻り値：
|     今回は保存処理を行わず、受信内容を画面に表示する。
|
| ▶ 実務では store() 内でバリデーション・DB保存・リダイレクトなどを行う。
*/
