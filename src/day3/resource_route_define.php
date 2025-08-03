<?php
// ==============================================
// Step3 教材②：Route::resource() を使ったルート定義と確認
// ファイル名：resource_route_define.php
// ==============================================

// 🧠 この教材のねらい
// ・Route::resource() を使うことで、RESTfulな7つのルートが一括で定義されることを確認する
// ・個別ルートと比較して、ルーティングの自動化・一貫性を理解する
// ・php artisan route:list コマンドで生成されるルート一覧を確認する

// ✅ 前提条件（index.php まで完了していることを想定）
// ・Laravelプロジェクトが起動済み（Docker + nginx環境）
// ・コントローラーを artisan コマンドで作成しておく（以下参照）


// ----------------------------------------------
// 🛠 ① コントローラーの作成
// ターミナルで以下の artisan コマンドを実行して、リソースコントローラーを作成
//
// $ php artisan make:controller TaskController --resource
//
// → app/Http/Controllers/TaskController.php が生成され、7つのアクションが自動定義される
//    index(), create(), store(), show(), edit(), update(), destroy()
// ----------------------------------------------


// ----------------------------------------------
// 🛠 ② web.php にルートを定義
// routes/web.php の中に、以下の1行を追加
//
// Route::resource('tasks', TaskController::class);
//
// → 自動的に7つのルートが生成される
// ----------------------------------------------


// ----------------------------------------------
// 🛠 ③ ルート一覧を確認
// ターミナルで以下を実行
//
// $ php artisan route:list
//
// 出力結果に以下のようなルートが含まれているはず：
//
// +--------+-----------+-------------------------+------------------+------------------------------+------------+
// | Method | URI       | Name                    | Action           | Middleware                   |
// +--------+-----------+-------------------------+------------------+------------------------------+------------+
// | GET    | /tasks    | tasks.index             | TaskController@index | web                   |
// | GET    | /tasks/create | tasks.create       | TaskController@create | web                  |
// | POST   | /tasks    | tasks.store             | TaskController@store  | web                  |
// | GET    | /tasks/{task} | tasks.show         | TaskController@show   | web                  |
// | GET    | /tasks/{task}/edit | tasks.edit   | TaskController@edit   | web                  |
// | PUT    | /tasks/{task} | tasks.update       | TaskController@update | web                  |
// | DELETE | /tasks/{task} | tasks.destroy     | TaskController@destroy| web                  |
// +--------+-----------+-------------------------+------------------+------------------------------+------------+
//
// 🔍 それぞれのメソッド・URI・アクションの対応関係を把握することが重要
// ----------------------------------------------


// ----------------------------------------------
// 🧩 補足（ルート名）
// 生成されたルートにはすべて「tasks.index」などの名前付きルートが自動で割り当てられる。
// → これにより、リンク生成などで route('tasks.index') のように使えるようになる
// ----------------------------------------------


// ----------------------------------------------
// 🧩 補足（コントローラーの構成）
// TaskController には以下の7メソッドが含まれる：
//
// - index()   → 一覧表示（GET /tasks）
// - create()  → 作成フォーム表示（GET /tasks/create）
// - store()   → 新規保存処理（POST /tasks）
// - show()    → 個別表示（GET /tasks/{id}）
// - edit()    → 編集フォーム表示（GET /tasks/{id}/edit）
// - update()  → 更新処理（PUT /tasks/{id}）
// - destroy() → 削除処理（DELETE /tasks/{id}）
// ----------------------------------------------


// ----------------------------------------------
// ✅ 確認ポイントまとめ
// ・Route::resource() は7つのルートを自動生成する便利な仕組み
// ・ルート名（tasks.indexなど）も自動で定義される
// ・artisan route:list で確認しながら、どのURLがどのメソッドに対応しているかを理解する
// ----------------------------------------------
