<?php
/*
📘 Step3 教材⑥：RESTfulルーティング後半（edit / update / destroy）

【学習目的】
・Route::resource() による edit / update / destroy のルート動作を理解する
・編集用フォーム → 更新処理（PUT）→ 削除処理（DELETE）までの一連のBlade連携を実装する
・form送信時の HTTPメソッドの指定（@method, @csrf）の正しい使い方を習得する

【ルーティング（自動生成）】
Route::resource('tasks', TaskController::class); により以下のルートが有効：

| メソッド | URL                       | アクション    | 用途                 |
|----------|---------------------------|---------------|----------------------|
| GET      | /tasks/{task}/edit        | edit          | 編集フォーム表示     |
| PUT      | /tasks/{task}             | update        | 更新処理（送信）     |
| DELETE   | /tasks/{task}             | destroy       | タスクの削除         |

---

【今回の実装対象】
① Controller：TaskController.php に edit / update / destroy を追加
② View（編集フォーム）：resources/views/tasks/edit.blade.php を新規作成
③ View（一覧画面）：resources/views/tasks/index.blade.php に「編集／削除」リンクを追加

---

【補足】
・edit は、指定IDのタスクをフォームに表示して編集できるようにする
・update は、PUTメソッドで編集結果を送信・保存する処理
・destroy は、DELETEメソッドでタスクを削除する処理
・PUT / DELETE は form の中で `@method('PUT')` などを使って指定する（HTMLではGET/POSTしか使えないため）

---

次に、TaskController.php に edit / update / destroy を追記していきます。
*/
