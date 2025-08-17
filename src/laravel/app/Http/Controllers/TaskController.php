<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    // 一覧表示（GET /tasks）
    public function index()
    {
        $tasks = Task::latest()->get(); // created_at の新しい順
        return view('tasks.index', compact('tasks'));
    }

    // 作成フォーム表示（GET /tasks/create）
    public function create()
    {
        return view('tasks.create');
    }

    // store（最小のバリデーション＋保存＋リダイレクト）
    public function store(TaskRequest $request)
    {
        $validated = $request->validated();
        Task::create($validated);

        return redirect()->route('tasks.index')
            ->with('status', 'タスクを作成しました');
    }


    // 個別表示（GET /tasks/{task}）
    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.show', ['task' => $task]);
    }

    // 編集フォーム表示（GET /tasks/{task}/edit）
    public function edit(string $id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', ['task' => $task]); // ← $taskを渡す
    }


    // update（最小のバリデーション＋更新）
    public function update(TaskRequest $request, string $id)
    {
        $validated = $request->validated();

        $task = Task::findOrFail($id);  // ← 現状のメソッドシグネチャを維持
        $task->update($validated);

        return redirect()->route('tasks.show', $task)
            ->with('status', 'タスクを更新しました');
    }

    // 削除処理（DELETE /tasks/{task}）
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')
        ->with('status', "ID: {$id}を削除しました");
    }
}
