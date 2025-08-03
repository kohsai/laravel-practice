<?php
// 【Laravel教材】Step3 教材①：RESTfulルーティングの基本とリソースルートの全体像

// ✅ 今回の目的
// - Laravelにおける RESTfulルーティング の考え方を整理する
// - Route::resource() の役割と、どのルートが自動生成されるかを理解する
// - HTTPメソッドの意味とCRUDの対応関係を明確にする

// --------------------------------------------------
// 🔰 HTTPメソッドとは？（REST設計の基礎）
// --------------------------------------------------

// HTTPメソッドとは、「サーバーにどうしてほしいか」を伝える命令です。
// 例えば「表示して」「登録して」「更新して」「削除して」といった操作を明確に区別するために使われます。

// Laravelで頻出する4つのHTTPメソッドは以下の通りです：

// | メソッド | 読み方   | 主な役割                   | Laravelでの使いどころ              |
// |----------|----------|----------------------------|------------------------------------|
// | GET      | ゲット   | データを取得する           | 一覧表示・詳細表示など             |
// | POST     | ポスト   | 新しいデータを作成する     | フォームの送信（登録処理）など     |
// | PUT      | プット   | 既存データを上書き更新     | 編集フォームの保存処理など         |
// | DELETE   | デリート | データを削除する           | 削除ボタンの処理など               |

// --------------------------------------------------
// 🧠 イメージしやすい例：商品リスト（items）
// --------------------------------------------------

// | 処理したい内容        | URL               | HTTPメソッド | 対応するメソッド |
// |-----------------------|-------------------|--------------|------------------|
// | 商品一覧を見たい       | /items            | GET          | index()          |
// | 新しい商品を登録したい | /items            | POST         | store()          |
// | 商品の詳細を見たい     | /items/{id}       | GET          | show()           |
// | 商品を更新したい       | /items/{id}       | PUT          | update()         |
// | 商品を削除したい       | /items/{id}       | DELETE       | destroy()        |

// 🔍 HTMLフォームでは GET/POST しか使えません。
// Laravelでは @method('PUT') や @method('DELETE') を使って、フォームからも対応できます。

// 例：Blade内でのPUTフォーム
/*
<form method="POST" action="/items/5">
    @csrf
    @method('PUT')
    <input type="text" name="name">
    <button type="submit">更新</button>
</form>
*/

// --------------------------------------------------
// ✅ Laravelにおける RESTfulルートと resource()
// --------------------------------------------------

// ● 通常のルート定義（7つ）を手動で書くと以下のようになります：

/*
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/create', [ItemController::class, 'create']);
Route::post('/items', [ItemController::class, 'store']);
Route::get('/items/{id}', [ItemController::class, 'show']);
Route::get('/items/{id}/edit', [ItemController::class, 'edit']);
Route::put('/items/{id}', [ItemController::class, 'update']);
Route::delete('/items/{id}', [ItemController::class, 'destroy']);
*/

// ● Laravelでは、これを1行でまとめて定義できます：
/*
Route::resource('items', ItemController::class);
*/

// --------------------------------------------------
// ✅ CRUD操作と自動生成ルートの一覧
// --------------------------------------------------

// | 処理     | メソッド | URL例             | コントローラのメソッド |
// |----------|----------|-------------------|--------------------------|
// | 一覧     | GET      | /items            | index()                  |
// | 作成画面 | GET      | /items/create     | create()                 |
// | 登録処理 | POST     | /items            | store()                  |
// | 詳細表示 | GET      | /items/{id}       | show()                   |
// | 編集画面 | GET      | /items/{id}/edit  | edit()                   |
// | 更新処理 | PUT      | /items/{id}       | update()                 |
// | 削除処理 | DELETE   | /items/{id}       | destroy()                |

// --------------------------------------------------
// ✅ 学習のポイントまとめ
// --------------------------------------------------

// - HTTPメソッドの役割を正確に理解することがRESTful設計の第一歩
// - Route::resource() を使えば、Laravelが必要なルートと構造を自動生成してくれる
// - 実務でもCRUDを扱う場面ではこのルーティングパターンが一般的

// 次の教材②では、実際にルートとコントローラを作成し、動作確認を行います。
