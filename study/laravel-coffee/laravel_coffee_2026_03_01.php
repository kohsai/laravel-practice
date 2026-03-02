<?php
// ☕ Laravelコーヒー 第1問（テーマ：ルーティング・Day2〜3）

// 🎯 【穴埋め問題】
// 次のコードは「resourceルート」を定義しています。
// このとき、自動的に生成される7つのルートのうち、
// 「新規作成フォームを表示する」アクションに対応する
// HTTPメソッドとURIの組み合わせはどれですか？

// Route::resource('articles', ArticleController::class);

// 🔤 【選択肢】
// 1. GET　/articles
// 2. GET　/articles/create
// 3. POST　/articles
// 4. GET　/articles/{article}/edit

// 💡 【ヒント】
// 「フォームを表示する」だけで、まだデータを送信していない段階です。
// データを「送る」のはその次のステップです。

// ✅ 【回答欄】
// 回答：___2___
// 「送信していない」ので、３の「post」は違うとして、
// 「新規作成」なので、「create」かな？と思います。

// 🧠 【採点と解説】
// 回答：2（GET /articles/create）✅
//
// 理由付けも完璧です。
// 「フォームを表示するだけ」→ GET
// 「データを送信する」→ POST（これは3番：POST /articles）
//
// resourceルートの7つを整理：
// 1. GET    /articles           → index（一覧表示）
// 2. GET    /articles/create    → create（新規作成フォーム表示）
// 3. POST   /articles           → store（新規作成・保存）
// 4. GET    /articles/{id}      → show（1件表示）
// 5. GET    /articles/{id}/edit → edit（編集フォーム表示）
// 6. PUT    /articles/{id}      → update（更新・保存）
// 7. DELETE /articles/{id}      → destroy（削除）


// ☕ Laravelコーヒー 第2問（テーマ：Eloquent・Day5）

// 🎯 【穴埋め問題】
// 次のコードは「ID=1のユーザーの支出記録を、
// 金額が500円以上のものだけ取得する」処理です。
// A・Bに入る適切なコードを選んでください。

// User::find(1)->expenses()->A('amount', '>=', 500)->B();

// 🔤 【選択肢】
// A の選択肢：
// 1. find
// 2. where
// 3. get
// 4. first

// B の選択肢：
// 1. all
// 2. find
// 3. count
// 4. get

// 💡 【ヒント】
// 「条件を絞り込む」命令と「実際にデータを取得する」命令の組み合わせです。

// ✅ 【回答欄】
// A：___2____
// B：___4____
// 「条件を指定する」ので「where」、
// 「取得する」ので、「get」かな？と思います。

// 🧠 【採点と解説】
// A：2（where）✅　B：4（get）✅
//
// whereは「条件を絞り込む準備」、getは「実際にデータを取得する」命令。
// この2つはセットで使うことが多い。
//
// 【expenses と expenses() の違いの復習】
// ->expenses        → すぐに全データ取得（条件追加不可）
// ->expenses()      → クエリビルダを返す（where・count等が使える）
//
// 今回は条件（where）を追加したいので expenses() を使う。


// ☕ Laravelコーヒー 第3問（テーマ：ルーティング・Day2〜3）

// 🎯 【選択問題】
// 次の4つのルート定義のうち、
// 「フォームから送信されたデータを受け取って保存する」
// アクションに対応する正しいものはどれですか？

// 1. Route::get('/articles', [ArticleController::class, 'index']);
// 2. Route::post('/articles', [ArticleController::class, 'store']);
// 3. Route::get('/articles/create', [ArticleController::class, 'create']);
// 4. Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);

// 💡 【ヒント】
// フォームから「送信」するときのHTTPメソッドを思い出してください。
// また、「保存する」に対応するアクション名も手がかりです。

// ✅ 【回答欄】
// 回答：___2____
// 「get」は表示するだけ、「delete」はデータを消去する。
// そして「index」は一覧表示、「store」が保存する、
// 「create」は新規作成、destroyは消去する、なので、
// ２となります。

// 🧠 【採点と解説】
// 回答：2（Route::post('/articles', [ArticleController::class, 'store'])）✅
//
// 「フォームから送信」→ POST
// 「保存する」→ store
// この2つの組み合わせが正解。
//
// 【7つのリソースルートの復習】
// GET    /articles           → index（一覧表示）
// GET    /articles/create    → create（新規作成フォーム表示）
// POST   /articles           → store（新規作成・保存）
// GET    /articles/{id}      → show（1件表示）
// GET    /articles/{id}/edit → edit（編集フォーム表示）
// PUT    /articles/{id}      → update（更新・保存）
// DELETE /articles/{id}      → destroy（削除）
//
// 「フォームを表示する」→ GET + create
// 「フォームを送信して保存する」→ POST + store
// この2つはセットで覚えると良い。


// ☕ Laravelコーヒー 第4問（テーマ：マイグレーション・Day5）

// 🎯 【穴埋め問題】
// 次のコードは「expensesテーブルを作成する」マイグレーションです。
// A・Bに入る適切なコードを選んでください。

// public function up(): void
// {
//     Schema::A('expenses', function (Blueprint $table) {
//         $table->id();
//         $table->B()->constrained()->onDelete('cascade');
//         $table->string('category');
//         $table->integer('amount');
//         $table->timestamps();
//     });
// }

// 🔤 【選択肢】
// A の選択肢：
// 1. table
// 2. create
// 3. add
// 4. make

// B の選択肢：
// 1. foreignId('user_id')
// 2. integer('user_id')
// 3. string('user_id')
// 4. foreign('user_id')

// 💡 【ヒント】
// Aは「新しくテーブルを作る」命令です。
// Bは「外部キーを安全に設定する」専用の命令です。

// ✅ 【回答欄】
// A：___2____
// B：___1____
// 新しく作るなので「create」、
// 外部キーなので「foreignId」かな？と思います。

// 🧠 【採点と解説】
// A：2（create）✅　B：1（foreignId('user_id')）✅
//
// 【Aの解説】
// Schema::create()  → 新しくテーブルを作る
// Schema::table()   → 既存のテーブルを変更する（カラム追加など）
// 新規作成なので create が正解。
//
// 【Bの解説】
// foreignId('user_id')  → 外部キー専用の命令（unsignedBigIntegerと同じ）
// integer('user_id')    → ただの整数カラム（外部キー制約なし）
// constrained()         → 自動的に users.id と紐付ける
// onDelete('cascade')   → 親（user）が削除されたら子（expense）も削除
//
// 【セットで覚える】
// $table->foreignId('user_id')->constrained()->onDelete('cascade');
// これで「usersテーブルのidと紐付いた安全な外部キー」が完成。


// ☕ Laravelコーヒー 第5問（テーマ：Blade・Day2）

// 🎯 【穴埋め問題】
// 次のコードは「$usersの中身を1件ずつ取り出して表示する」
// Bladeテンプレートです。
// A・Bに入る適切なコードを選んでください。

// A ($users as $user)
//     <p>{{ $user->name }}</p>
// B

// 🔤 【選択肢】
// A の選択肢：
// 1. @for
// 2. @foreach
// 3. @if
// 4. @while

// B の選択肢：
// 1. @endfor
// 2. @endif
// 3. @endforeach
// 4. @endwhile

// 💡 【ヒント】
// 「複数のデータを1件ずつ取り出す」命令です。
// AとBは必ずセットで使います。

// ✅ 【回答欄】
// A：___2____
// B：___3____
// 「複数のデータを一件ずつ取り出す」ので
// foreachかな？と思います。

// 🧠 【採点と解説】
// A：2（@foreach）✅　B：3（@endforeach）✅
//
// 【Bladeディレクティブの対応関係】
// @foreach  ↔  @endforeach
// @if       ↔  @endif
// @for      ↔  @endfor
// @while    ↔  @endwhile
// 開始タグと終了タグは必ずセットで使う。
//
// 【@foreachの使い方】
// @foreach ($users as $user)
//     <p>{{ $user->name }}</p>
// @endforeach
//
// PHPのforeach文と同じ意味だが、「<？php ？>」が不要でスッキリ書ける。
// {{ }} = PHPの echo と同じ（XSS対策済み）


// ☕ Laravelコーヒー 第6問（テーマ：Eloquentリレーション・Day5）

// 🎯 【選択問題】
// 次のコードを実行すると、どんな結果になりますか？

// $expense = Expense::find(2);
// echo $expense->user->name;

// 🔤 【選択肢】
// 1. ID=2の支出記録を持つユーザーの名前が表示される
// 2. ID=2のユーザーの名前が表示される
// 3. ID=2の支出記録の金額が表示される
// 4. エラーが発生する

// 💡 【ヒント】
// Expense::find(2) で何が取得されるか、
// そして ->user で何が取得されるかを順番に考えてみてください。

// ✅ 【回答欄】
// 回答：___1____
// $expense = Expense::find(2);で
// 「Id=2のユーザーの支出」を指定し、
// echo $expense->user->name;で
// そのId=2のユーザーの名前を取得しているので、
// １となります。

// 🧠 【採点と解説】
// 回答：1（ID=2の支出記録を持つユーザーの名前が表示される）✅
//
// 【コードを順番に読む】
// $expense = Expense::find(2);
// → 「ID=2の支出記録」を取得して$expenseに入れる
//
// $expense->user
// → 「その支出記録の持ち主（ユーザー）」を取得
// → belongsTo(User::class)が動く
//
// ->name
// → 「そのユーザーの名前」を取り出す
//
// 【2番との違い】
// 1番：ID=2の「支出記録」の持ち主の名前
// 2番：ID=2の「ユーザー」の名前
// Expense::find(2)はユーザーではなく支出記録を探している点が重要。


// ☕ Laravelコーヒー 第7問（テーマ：Eloquent基本操作・Day5）

// 🎯 【穴埋め問題】
// 次のコードは「メールアドレスがtest@example.comの
// ユーザーを1件だけ取得する」処理です。
// A・Bに入る適切なコードを選んでください。

// $user = User::A('email', 'test@example.com')->B();

// 🔤 【選択肢】
// A の選択肢：
// 1. find
// 2. all
// 3. where
// 4. create

// B の選択肢：
// 1. get
// 2. first
// 3. all
// 4. count

// 💡 【ヒント】
// Aは「条件を指定する」命令、
// Bは「条件に合う中から1件だけ取得する」命令です。

// ✅ 【回答欄】
// A：___3____
// B：___1____
// 「条件を指定する」ので「where」、
// 「一件だけ取得する」ので「get」となります。

// 🧠 【採点と解説】
// A：3（where）✅　B：2（first）❌（回答：1 get）
//
// 【get()とfirst()の違い】
// ->get()   → 条件に合う「全件」をCollectionで取得
// ->first() → 条件に合う「最初の1件」だけを取得
//
// 【使い分け】
// 複数件取得したい → where()->get()
// 1件だけ取得したい → where()->first()
//
// 【今回のケース】
// メールアドレスは1人に1つなので結果は必ず1件。
// そのため first() が適切。
//
// 【覚え方】
// first = 「最初の1件」
// get   = 「全部持ってくる」


// ☕ Laravelコーヒー 第8問（テーマ：Eloquentリレーション・Day5）

// 🎯 【選択問題】
// UserモデルにExpenseとのリレーションを定義します。
// 「1人のユーザーは複数の支出記録を持つ」関係を表す
// 正しいコードはどれですか？

// 1. public function expenses()
//    {
//        return $this->belongsTo(Expense::class);
//    }

// 2. public function expense()
//    {
//        return $this->hasMany(Expense::class);
//    }

// 3. public function expenses()
//    {
//        return $this->hasMany(Expense::class);
//    }

// 4. public function expenses()
//    {
//        return $this->hasOne(Expense::class);
//    }

// 💡 【ヒント】
// 2つのポイントを確認してください。
// ①「1対多」の「親」側に使うメソッド名
// ②「複数」を表すメソッド名（単数形・複数形）

// ✅ 【回答欄】
// 回答：___3____
// 「1対多」は「hasmany」、
// 「複数」なので「expenses」となります。

// 🧠 【採点と解説】
// 回答：3✅
//
// 【2つのポイント】
// ① hasMany → 「1対多」の親側（1人が複数を持つ）
//   belongsTo → 「1対多」の子側（1つが1人に属する）
//   hasOne    → 「1対1」の親側
//
// ② メソッド名は「複数形」→ expenses（○）/ expense（×）
//   「1人は複数の支出記録を持つ」→ 複数形が自然
//
// 【各選択肢の何が間違いか】
// 1番：belongsToは「子」側の命令。Userは「親」なので不正解。
// 2番：hasManyは正しいがメソッド名が単数形（expense）なので不正解。
// 4番：hasOneは「1対1」の命令。1人が1件しか持てない意味になる。


// ☕ Laravelコーヒー 第9問（テーマ：認証・Day4）

// 🎯 【選択問題】
// 次のルートは「ログインしているユーザーだけがアクセスできる」
// ページを定義しています。
// （　）に入る正しいコードはどれですか？

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware('（　）');

// 🔤 【選択肢】
// 1. guest
// 2. verified
// 3. auth
// 4. admin

// 💡 【ヒント】
// 前回のセッションで間違えたテーマです。
// 「ログイン済みユーザーだけ通す」か
// 「ログインしていないユーザーだけ通す」かを思い出してください。

// ✅ 【回答欄】
// 回答：___3____
// 前回は「guest」で間違えた気がしますので、
// 今回は「auth」だと思います。

// 🧠 【採点と解説】
// 回答：3（auth）✅
//
// 【auth と guest の違い】
// auth  → ログイン済みユーザーだけ通す（未ログインはログインページへ）
// guest → 未ログインユーザーだけ通す（ログイン済みはトップページへ）
//
// 【使い分け】
// ->middleware('auth')  → ダッシュボード・マイページ等
// ->middleware('guest') → ログインページ・会員登録ページ等
//
// 【覚え方】
// auth  = authenticated（認証済み）の略
// guest = ゲスト（まだ会員ではない人）
//
// 【2番・4番の補足】
// verified（バーファイド）
// → メール認証済みユーザーだけ通すmiddleware
// → 登録後にメール認証を完了した人のみアクセス可能
// → authと組み合わせて使うことが多い
//
// admin
// → Laravelに最初から用意されているものではない
// → 「管理者だけ通す」独自のmiddlewareを自分で作る場合の名前の例
// → 実際に使うにはMiddlewareクラスを自分で作成する必要がある


// ☕ Laravelコーヒー 第10問（テーマ：Eloquent基本操作・Day5）

// 🎯 【穴埋め問題】
// 次のコードは「新しいユーザーを作成する」処理です。
// （　）に入る正しいコードを選んでください。

// User::（　）([
//     'name'     => '山田太郎',
//     'email'    => 'yamada@example.com',
//     'password' => bcrypt('password123'),
// ]);

// 🔤 【選択肢】
// 1. save
// 2. insert
// 3. create
// 4. store

// 💡 【ヒント】
// 複数のカラムを一度に代入して保存する命令です。
// $fillableと組み合わせて使います。

// ✅ 【回答欄】
// 回答：___3____
// 直感です。２と４は違うという直感で、
// １と少し迷いましたが。

// 🧠 【採点と解説】
// 回答：3（create）✅
//
// 【create()とsave()の違い】
// User::create([...])
// → 複数カラムを一度に代入して保存（1行で完結）
// → $fillableで許可したカラムのみ保存される
//
// $user = new User();
// $user->name = '山田太郎';
// $user->save();
// → 1つずつカラムに代入してから保存（複数行が必要）
//
// 【2番・4番について】
// insert() → LaravelのDBファサードで使う低レベルな命令
//            Eloquentモデルでは使わない
// store()  → コントローラーのメソッド名として使う
//            Eloquentの命令ではない
//
// 【覚え方】
// create() = 「材料（配列）を渡して一気に作る」
// save()   = 「1つずつ組み立ててから保存する」

