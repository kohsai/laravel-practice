<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    // 一覧表示（GET /tasks）
    public function index()
    {
        return 'タスク一覧を表示します';
    }

    // 作成フォーム表示（GET /tasks/create）
    public function create()
    {
        return 'タスク作成フォームを表示します';
    }

    // 保存処理（POST /tasks）
    public function store(Request $request)
    {
        return 'タスクを保存しました（仮）';
    }

    // 個別表示（GET /tasks/{task}）
    public function show(string $id)
    {
        return "ID: {$id} のタスクを表示します";
    }

    // 編集フォーム表示（GET /tasks/{task}/edit）
    public function edit(string $id)
    {
        return "ID: {$id} のタスク編集フォームを表示します";
    }

    // 更新処理（PUT /tasks/{task}）
    public function update(Request $request, string $id)
    {
        return "ID: {$id} のタスクを更新しました（仮）";
    }

    // 削除処理（DELETE /tasks/{task}）
    public function destroy(string $id)
    {
        return "ID: {$id} のタスクを削除しました（仮）";
    }
}
