<?php
// ---------------------------------------------
// 📘 Step3：RESTful設計とルーティングの概要
// ファイル名：restful_routes_summary.php
// ---------------------------------------------

// ✅ この教材では、Laravelの「RESTful設計」の基本を学びます。
// 特に「7つのアクション」と「リソースルーティング」の考え方を中心に進めます。

// ---------------------------------------------
// 🔰 【1】RESTful設計とは？
// ---------------------------------------------

// ✅ REST（Representational State Transfer）とは：
// Webサービスの設計思想の1つ。
// Laravelでは「ルート・コントローラ・ビュー」の対応関係を
// より分かりやすく・機能ごとに整理するために、RESTful構造が推奨されます。

// ✅ RESTfulに基づいたルーティングとは：
// 「1つのURLリソース（例：/articles）」に対して、
// HTTPメソッドごとに異なる処理（一覧・詳細・作成など）を割り当てます。

// ---------------------------------------------
// 🔢 【2】RESTfulな7つのアクション（CRUD）
// ---------------------------------------------

// Laravelでは、次の7つのルートとコントローラメソッドが1セットです。

// | HTTPメソッド | URL例             | コントローラメソッド | 意味         |
// |--------------|------------------|----------------------|--------------|
// | GET          | /articles         | index()              | 一覧表示     |
// | GET          | /articles/create  | create()             | 作成フォーム |
// | POST         | /articles         | store()              | 保存処理     |
// | GET          | /articles/{id}    | show()               | 詳細表示     |
// | GET          | /articles/{id}/edit | edit()            | 編集フォーム |
// | PUT/PATCH    | /articles/{id}    | update()             | 更新処理     |
// | DELETE       | /articles/{id}    | destroy()            | 削除処理     |

// ✅ この構造に従って、Laravelでは次のようにルートを1行で定義できます：
// Route::resource('articles', ArticleController::class);

// これで、上記7つのルートがすべて自動生成されます。

// ---------------------------------------------
// 🔎 【3】実際にどんなルートが作られるのか？
// ---------------------------------------------

// 以下のコマンドでルーティング一覧を確認できます。
// $ php artisan route:list

// 表示結果例：
// | Method   | URI                     | Action                          |
// |----------|-------------------------|---------------------------------|
// | GET      | articles                | ArticleController@index         |
// | GET      | articles/create         | ArticleController@create        |
// | POST     | articles                | ArticleController@store         |
// | GET      | articles/{article}      | ArticleController@show          |
// | GET      | articles/{article}/edit | ArticleController@edit          |
// | PUT/PATCH| articles/{article}      | ArticleController@update        |
// | DELETE   | articles/{article}      | ArticleController@destroy       |

// ---------------------------------------------
// 🧭 【4】次にやること（準備）
// ---------------------------------------------

// 1. RESTfulルートを定義する resource() を試す
// 2. ArticleController を artisan コマンドで作成する
// 3. 作られた7つのメソッドの雛形を確認する
// 4. 表示できるよう index() や create() に簡易ビューを用意する

// ✅ 上記を順に教材として進めます。
// まずは「Route::resource」の記述とルートの確認から始めましょう。
