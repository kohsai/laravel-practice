<?php
// ==============================================
// Step3 教材③：各アクションメソッドの基本実装と確認
// ファイル名：resource_action_views.php
// ==============================================

// 📌 ファイル作成コマンド（ターミナル）
// touch src/day3/resource_action_views.php

// 🧠 この教材の目的
// ・TaskControllerの各アクション（index, create など）に簡易な処理を追加し、
//   ブラウザでアクセスしてルートとの対応関係を確認する。
// ・まずは return 文で文字列を返すだけにして、動作確認に集中する。

// ✅ 対象コントローラー：app/Http/Controllers/TaskController.php
// 以下のコードを TaskController クラス内に実装してください。
// （このファイル自体は教材記録用であり、Laravelから実行されるものではありません）



// ------------------------------------------------------
// 📌 TaskController 各メソッドの仮実装（return文で確認用）
// ------------------------------------------------------

// 一覧表示（GET /tasks）
/*
public function index()
{
    return 'タスク一覧を表示します';
}
*/

// 作成フォーム表示（GET /tasks/create）
/*
public function create()
{
    return 'タスク作成フォームを表示します';
}
*/

// 保存処理（POST /tasks）
/*
public function store(Request $request)
{
    return 'タスクを保存しました（仮）';
}
*/

// 個別表示（GET /tasks/{task}）
/*
public function show(string $id)
{
    return "ID: {$id} のタスクを表示します";
}
*/

// 編集フォーム表示（GET /tasks/{task}/edit）
/*
public function edit(string $id)
{
    return "ID: {$id} のタスク編集フォームを表示します";
}
*/

// 更新処理（PUT /tasks/{task}）
/*
public function update(Request $request, string $id)
{
    return "ID: {$id} のタスクを更新しました（仮）";
}
*/

// 削除処理（DELETE /tasks/{task}）
/*
public function destroy(string $id)
{
    return "ID: {$id} のタスクを削除しました（仮）";
}
*/



// ------------------------------------------------------
// ✅ ブラウザでの確認ポイント（GETメソッド）
// ------------------------------------------------------
//
// ・/tasks               → 「タスク一覧を表示します」
// ・/tasks/create        → 「タスク作成フォームを表示します」
// ・/tasks/3             → 「ID: 3 のタスクを表示します」
// ・/tasks/3/edit        → 「ID: 3 のタスク編集フォームを表示します」
//
// ※ POST/PUT/DELETE の確認はフォームが必要なため、次の教材で行います
// ------------------------------------------------------
