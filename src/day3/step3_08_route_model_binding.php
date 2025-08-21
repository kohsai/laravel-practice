<?php
/*
【Step3 教材⑧：ルートモデルバインディングへのリファクタリング】

■ 対象ファイル：
src/laravel/app/Http/Controllers/TaskController.php

■ 概要：
TaskControllerの下記4メソッドについて、$idを受け取ってfindOrFail()を実行する従来の形式から、
Laravelの「ルートモデルバインディング」を活用した形式にリファクタリングを行う。

■ 修正内容：
【1】各メソッドの引数： string $id → Task $task に変更
【2】Task::findOrFail($id); の行を削除
【3】既存の return や update, delete 処理などはそのまま活用
【4】メッセージ中の $id は $task->id に変更（destroyのみ）

■ 修正対象メソッドと変更後の構文：

① show(Task $task)
    → return view('tasks.show', ['task' => $task]);

② edit(Task $task)
    → return view('tasks.edit', ['task' => $task]);

③ update(TaskRequest $request, Task $task)
    → $task->update($validated);
    → return redirect()->route('tasks.show', $task);

④ destroy(Task $task)
    → $task->delete();
    → return redirect()->route('tasks.index')->with('status', "ID: {$task->id}を削除しました");

■ 解説：
Laravelでは、ルートのURL（例：/tasks/3）に含まれるID部分をもとに、該当するTaskモデルを自動的に取得してくれます。
この仕組みを「ルートモデルバインディング」と呼びます。

これにより、Task::findOrFail($id) を明示的に書かなくても済み、コードが簡潔かつ安全になります。
存在しないIDが指定された場合は、自動的に404エラー画面が表示されます。

■ 検証ポイント：
・/tasks/{id} アクセスで詳細表示できる
・/tasks/{id}/edit → 編集・更新が正常に動作
・削除も問題なく動作
・存在しないIDでアクセスすると404になる

*/
