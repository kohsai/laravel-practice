<?php
// ☕ Laravelコーヒー 第1問（テーマ：ルーティング・Day1〜3）

// 🎯 【穴埋め問題】
// 次のコードは「/profile」にアクセスしたとき、
// ProfileControllerのshowメソッドを呼び出すルートです。
// A・Bに入る適切なコードを記入してください。

// Route::A('/profile', [ProfileController::class, 'B']);

// 🔤 【選択肢】
// A の選択肢：
// 1. post
// 2. get
// 3. put
// 4. delete

// B の選択肢：
// 1. index
// 2. create
// 3. show
// 4. destroy

// ✅ 【回答欄】
// A：___２____
// B：___３____

// ただページを見るのは「get」なので「２」，
// 「showメソッド」と問題で言ってるので「３」です。
// Kloge、問題のレベルを「少しだけ」上げてください。
// ヒントを見れば必ず分かる、と言うより、
// 「ヒントがあって何となく気づける、思い出せる」ように
// お願いします。もちろん、学習した範囲からなので「思い出せる」はずです。

// 🧠 【採点と解説】
// A：2（get）✅　B：3（show）✅
//
// GETはデータを「取得・表示」するときに使う。
// POSTは「送信・作成」、PUTは「更新」、DELETEは「削除」。
// showは「1件表示」。indexは「一覧表示」。
// 7つのリソースルート：index/create/store/show/edit/update/destroy


// ☕ Laravelコーヒー 第2問（テーマ：認証・Day4）

// 🎯 【穴埋め問題】
// 次のコードは「ログインしていないユーザーを
// ログインページにリダイレクトさせる」処理です。
// A・Bに入る適切なコードを記入してください。

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->A(B);

// 🔤 【選択肢】
// A の選択肢：
// 1. middleware
// 2. prefix
// 3. name
// 4. group

// B の選択肢：
// 1. 'guest'
// 2. 'verified'
// 3. 'auth'
// 4. 'admin'

// ✅ 【回答欄】
// A：___1____
// B：___1____

// 正直、全くピンと来てません。
// かなり難易度が上がった感じです（笑）
// 直感で、見たことがある気がする「middleware」と、
// 「ログインしていないユーザー（ゲスト？）」なので、
// 「guest」かな？とこれも直感です。

// 🧠 【採点と解説】
// A：1（middleware）✅　B：3（auth）❌
//
// middleware('auth')  → ログイン済みユーザーだけ通す
// middleware('guest') → 未ログインユーザーだけ通す（ログインページ等）
// 問題文は「未ログインをリダイレクトさせる」＝ログイン済みのみ通したい→auth
// 混乱しやすいポイント：「守りたい対象」で覚える
// dashboardはログイン済みの人だけ見せたい → auth


// ☕ Laravelコーヒー 第3問（テーマ：Eloquent基本操作・Day5）

// 🎯 【穴埋め問題】
// 次のコードは「メールアドレスが test@example.com のユーザーを
// 1件だけ取得する」処理です。
// A・Bに入る適切なコードを記入してください。

// $user = User::A('email', 'test@example.com')->B();

// 🔤 【選択肢】
// A の選択肢：
// 1. all
// 2. find
// 3. where
// 4. get

// B の選択肢：
// 1. all
// 2. first
// 3. get
// 4. find

// ✅ 【回答欄】
// A：___3____
// B：___2____

// 条件を付けるので「where」かな？と思います。
// 一件だけ取得するので「first」かな？と思います。
// 自信はありません。

// 🧠 【採点と解説】
// A：3（where）✅　B：2（first）✅
//
// where() = 条件を指定する（「〜のユーザーを探して」）
// first() = 条件に合う中で最初の1件だけ取得
// get()   = 条件に合う全件を取得（複数形）
//
// よくあるセット：
// User::where('email', 'xxx')->first(); // 1件
// User::where('active', 1)->get();      // 複数件

// 【追記：firstとfindの使い分け】
//
// find()  = IDにしか使えない
//   User::find(1);         // OK
//   User::find('田中');    // NG
//
// where()->first() = 条件に合う「最初の」1件
// where()->get()   = 条件に合う「全件」
//
// 苗字・年齢など重複ありの場合：
//   User::where('last_name', '田中')->first(); // 最初の1件
//   User::where('last_name', '田中')->get();   // 全員
//
// メールアドレスはユニーク前提なので
// where()->first() で実質1件になるが、
// 仕組みとしては「最初の1件」であることに注意。


// ☕ Laravelコーヒー 第4問（テーマ：リレーション・Day5）

// 🎯 【穴埋め問題】
// 次のコードは「1人のユーザーが複数の投稿を持つ」
// リレーションをUserモデルに定義しています。
// A・Bに入る適切なコードを記入してください。

// class User extends Model
// {
//     public function posts()
//     {
//         return $this->A();
//     }
// }

// 🔤 【選択肢】
// A の選択肢：
// 1. belongsTo
// 2. hasOne
// 3. hasMany
// 4. belongsToMany

// ✅ 【回答欄】
// A：___3____
// 一人のユーザーが「複数の（many）」投稿を「持つ（has）」なので、
// 3かと思います。

// 🧠 【採点と解説】
// A：3（hasMany）✅
//
// リレーションの4種類：
// hasMany      = 1対多（ユーザーが複数の投稿を持つ）← 今回
// hasOne       = 1対1（ユーザーが1つのプロフィールを持つ）
// belongsTo    = 多対1（投稿はユーザーに属する）
// belongsToMany = 多対多（ユーザーが複数のタグを持ち、タグも複数のユーザーに属する）
//
// 覚え方：
// has〜    = 「持つ側」に定義する（User が Post を持つ → Userモデルに書く）
// belongsTo = 「属する側」に定義する（Post が User に属する → Postモデルに書く）


// ☕ Laravelコーヒー 第5問（テーマ：ルーティング・Day2〜3）

// 🎯 【穴埋め問題】
// 次のコードは「リソースルーティング」を定義しています。
// A・Bに入る適切なコードを記入してください。

// Route::A('B', PostController::class);

// 🔤 【選択肢】
// A の選択肢：
// 1. get
// 2. post
// 3. resource
// 4. middleware

// B の選択肢：
// 1. '/posts'
// 2. '/post'
// 3. '/posts/index'
// 4. '/posts/all'

// ✅ 【回答欄】
// A：___3____
// B：___1____

// リソースルーティング（一つの設定で７つ？のルーティングを構築）なので、
// 3の「resource」、/postsか/postか迷いましたが
// 複数ルーティングを設定するので「posts」かな？と思います。

// 🧠 【採点と解説】
// A：3（resource）✅　B：1（'/posts')✅
//
// Route::resource('/posts', PostController::class);
// この1行で以下の7つのルートが自動生成される：
//
// GET    /posts           → index   （一覧）
// GET    /posts/create    → create  （作成フォーム）
// POST   /posts           → store   （保存）
// GET    /posts/{id}      → show    （1件表示）
// GET    /posts/{id}/edit → edit    （編集フォーム）
// PUT    /posts/{id}      → update  （更新）
// DELETE /posts/{id}      → destroy （削除）
//
// URLは複数形（/posts）が慣例。
// 理由：「投稿たちを扱う場所」というイメージ。
//
// 【追記：resourceと個別指定の違い】
//
// Route::resource('/posts', PostController::class);
// → 上記7つを「まとめて」登録する省略形
//
// Route::get('/posts', [PostController::class, 'index']);
// → index1つだけを「個別に」登録する
//
// 使い分け：
// 7つ全部使う → Route::resource() で一括
// 一部だけ使う → Route::get() 等で個別指定


// ☕ Laravelコーヒー 第6問（テーマ：Blade・Day2〜3）

// 🎯 【穴埋め問題】
// 次のコードはBladeテンプレートで
// 「$posts の中身を1件ずつ取り出してタイトルを表示する」処理です。
// A・Bに入る適切なコードを記入してください。

// A ($posts as $post)
//     <p>{{ $post->B }}</p>
// @endforeach

// 🔤 【選択肢】
// A の選択肢：
// 1. @for
// 2. @foreach
// 3. @if
// 4. @while

// B の選択肢：
// 1. title()
// 2. get('title')
// 3. title
// 4. ['title']

// ✅ 【回答欄】
// A：___2____
// B：___1____
// 直感ですが、最後に「@endforeach」とあるので、
// 対応して「@foreach」かな？と思います。
// <p></p>で囲っているので{{}}の中身は文字列にする（""で囲う）必要はなく、
// titleをすべて取得する「（）」ので「title（）」かな？と思います。

// 🧠 【採点と解説】
// A：2（@foreach）✅　B：3（title）❌
//
// @foreach ($posts as $post)
//     <p>{{ $post->title }}</p>
// @endforeach
//
// $post->title  = プロパティ（データを取り出すだけ）→ ()不要
// $post->title()= メソッド（命令を実行する）→ ()あり・意味が変わる
//
// 覚え方：
// ->name, ->title, ->email など「データを取り出す」→ ()なし
// ->save(), ->delete() など「何かを実行する」→ ()あり
//
// 【追記：()の意味と引数（ひきすう）】
//
// ()には2つの意味がある：
// 1. 「ここからメソッド（命令）ですよ」という印
// 2. 「材料（引数）が必要なら中に入れてください」という受け皿
//
// ()の中に何も入らない場合：
//   $post->save();    // 「保存して」とだけ伝える
//
// ()の中に材料（引数）を渡す場合：
//   User::find(1);                          // 「ID=1を探して」
//   User::where('email', 'test@example.com'); // 「この条件で探して」


// ☕ Laravelコーヒー 第7問（テーマ：マイグレーション・Day5）

// 🎯 【穴埋め問題】
// 次のコードはマイグレーションファイルの中身です。
// 「postsテーブルにtitleカラム（文字列）と
// user_idカラム（整数）を追加する」処理です。
// A・Bに入る適切なコードを記入してください。

// $table->A('title');
// $table->B('user_id');

// 🔤 【選択肢】
// A の選択肢：
// 1. integer
// 2. boolean
// 3. string
// 4. text

// B の選択肢：
// 1. string
// 2. boolean
// 3. text
// 4. integer

// ✅ 【回答欄】
// A：___3____
// B：___4____
// 「（短い）文字列」と「整数」を示す英語なので
// 「string」と「integer」だと思います。

// 🧠 【採点と解説】
// A：3（string）✅　B：4（integer）✅
//
// マイグレーションの主なカラム型：
// $table->string('name');   // 短い文字列（名前・メール等）
// $table->text('body');     // 長い文字列（本文・説明等）
// $table->integer('age');   // 整数（IDや個数等）
// $table->boolean('active');// true/false（ON/OFF等）
//
// 覚え方：
// 短い文字 → string（ストリング）
// 長い文字 → text（テキスト）
// 整数     → integer（インテジャー）
// 真偽値   → boolean（ブーリアン）


// ☕ Laravelコーヒー 第8問（テーマ：Eloquent基本操作・Day5）

// 🎯 【穴埋め問題】
// 次のコードは「新しいユーザーをデータベースに保存する」処理です。
// A・Bに入る適切なコードを記入してください。

// User::A([
//     'name'  => '田中太郎',
//     'email' => 'tanaka@example.com',
//     'password' => B('password123'),
// ]);

// 🔤 【選択肢】
// A の選択肢：
// 1. save
// 2. insert
// 3. create
// 4. store

// B の選択肢：
// 1. encrypt
// 2. bcrypt
// 3. hash
// 4. password

// ✅ 【回答欄】
// A：___3____
// B：___2____
// 両方とも直感です。
// 新しく作る（追加する）のは「create」だった気がして、
// パスワードに関しては「bcrypt（何て読む？）」が、「暗号化する」
// みたいなものだった気がして、勘です。

// 🧠 【採点と解説】
// A：3（create）✅　B：2（bcrypt）✅
//
// User::create([...]) = データを新規作成してDBに保存する
//
// bcrypt（ビークリプト）= パスワードを暗号化する関数
// 例：bcrypt('password123') → '$2y$10$...' のような文字列に変換
// 理由：パスワードをそのまま保存すると危険なため、
//       必ず暗号化してから保存する
//
// createとsaveの違い：
// User::create([...])       = 1行でまとめて作成・保存
// $user = new User;         = インスタンスを作って
// $user->name = '田中太郎'; = 1つずつセットして
// $user->save();            = 最後に保存（複数行）


// ☕ Laravelコーヒー 第9問（テーマ：ルーティング・Day1〜2）

// 🎯 【穴埋め問題】
// 次のコードは「/about にアクセスしたとき、
// about.blade.php を表示する」処理です。
// A・Bに入る適切なコードを記入してください。

// Route::get('/about', function () {
//     return A('B');
// });

// 🔤 【選択肢】
// A の選択肢：
// 1. echo
// 2. print
// 3. view
// 4. blade

// B の選択肢：
// 1. 'about.blade.php'
// 2. 'about'
// 3. '/about'
// 4. 'views.about'

// ✅ 【回答欄】
// A：___3____
// B：___2____
// ページを表示するので「view」かと思います。
// 「'about'」だけで十分かは確信がないですが
// １と４は長すぎて違うと感じ、３は上の行の中にあるので、
// ２かな？と思います。

// 🧠 【採点と解説】
// A：3（view）✅　B：2（'about'）✅
//
// return view('about');
// → resources/views/about.blade.php を表示する
//
// 【補足：なぜ'about'だけでいいのか】
// Laravelは自動的に resources/views/ を見に行くため
// ファイル名から '.blade.php' を省略して書くのが慣例
//
// サブフォルダにある場合はドット(.)で区切る：
// return view('user.profile');
// → resources/views/user/profile.blade.php を表示する


// ☕ Laravelコーヒー 第10問（テーマ：Eloquent リレーション・Day5）

// 🎯 【穴埋め問題】
// 次のコードは「投稿（Post）がどのユーザーに属しているかを
// 取得する」リレーションをPostモデルに定義しています。
// A・Bに入る適切なコードを記入してください。

// class Post extends Model
// {
//     public function A()
//     {
//         return $this->B();
//     }
// }

// 🔤 【選択肢】
// A の選択肢：
// 1. posts
// 2. user
// 3. users
// 4. post

// B の選択肢：
// 1. hasMany
// 2. hasOne
// 3. belongsToMany
// 4. belongsTo

// ✅ 【回答欄】
// A：___2____
// B：___4____
// 単数か複数か迷いましたが「個別のユーザー」を指定するのかな？と思い、
// 「user」にしました。belongsToは勘です。

// 🧠 【採点と解説】
// A：2（user）✅　B：4（belongsTo）✅
//
// class Post extends Model
// {
//     public function user()
//     {
//         return $this->belongsTo(User::class);
//     }
// }
//
// メソッド名は単数形（user）：
// 理由：1つの投稿は1人のユーザーにしか属さないから
//
// 第4問との対比：
// User モデル → public function posts() → hasMany    （1対多の「持つ側」）
// Post モデル → public function user()  → belongsTo  （1対多の「属する側」）
//
// 覚え方：
// 「誰かに属している」→ belongsTo・単数形
// 「複数を持っている」→ hasMany・複数形