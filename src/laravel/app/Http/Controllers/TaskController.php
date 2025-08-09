<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    // 一覧表示（GET /tasks）
    public function index()
    {
        return view('tasks.index');
    }

    // 作成フォーム表示（GET /tasks/create）
    public function create()
    {
        return view('tasks.create');
    }

    // 保存処理（POST /tasks）
    public function store(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');

        return "タイトル：{$title} <br> 詳細：{$description}";
    }

    // 個別表示（GET /tasks/{task}）
    public function show(string $id)
    {
        return view('tasks.show', ['id' => $id]);
    }

    // 編集フォーム表示（GET /tasks/{task}/edit）
    public function edit(string $id)
    {
        return view('tasks.edit', ['id' => $id]);
    }

    // 更新処理（PUT /tasks/{task}）
    public function update(Request $request, string $id)
    {
        // (仮) 更新処理
        $title = $request->input('title');
        $description = $request->input('description');

        return redirect()->route('tasks.index')
            ->with('status', "ID: {$id} を更新しました（仮）タイトル：{$title}／詳細：{$description}");
    }

    // 削除処理（DELETE /tasks/{task}）
    public function destroy(string $id)
    {
        // (仮) 削除処理
        return redirect()->route('tasks.index')
            ->with('status', "ID: {$id} を削除しました（仮）");
    }
}
