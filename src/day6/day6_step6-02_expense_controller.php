<?php

/**
 * 📘 Day6 教材（Step6-02：ExpenseControllerの作成とRoute::resource設定）
 *
 * この教材では「支出を管理するコントローラーの作成と
 * ルーティングの設定」を学びます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * artisan（アーティザン）コマンドを使って
 * ExpenseController（エクスペンスコントローラー）を作成し、
 * Route::resource（ルートリソース）でルーティングを設定します。
 *
 * 【今日学ぶ2つのこと】
 * 1. コントローラーの自動生成（--resource オプション）
 * 2. Route::resource によるルート一括設定
 *
 * 【Laravelの流れのおさらい】
 * ブラウザ → web.php（道案内）→ Controller（処理）→ Model（DB操作）
 *
 * web.php = メニュー表（どのURLにアクセスしたらどこに行くか）
 * Controller = ウェイター（注文を受けて処理する）
 * Model = 厨房（データを保存・取り出す）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. ExpenseControllerの作成】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * artisan（アーティザン）= Laravelの「なんでも屋さん」コマンドツール
 *
 * 【コマンドの意味】
 * docker compose exec php = phpコンテナの中で実行する
 * php artisan = Laravelの便利ツール（artisan）をPHPで動かす
 * make:controller = コントローラーファイルを自動生成する
 * ExpenseController = 作るファイルの名前
 * --resource = CRUDの7メソッド雛形付きで作成する
 *
 * 【--resource（リソース）オプションとは？】
 * --resource をつけると、以下の7つのメソッドが最初から入った
 * コントローラーが作られます。
 *
 * index()   = 一覧表示
 * create()  = 新規作成フォーム表示
 * store()   = 新規作成処理（DBに保存）
 * show()    = 1件表示
 * edit()    = 編集フォーム表示
 * update()  = 編集処理（DBを更新）
 * destroy() = 削除処理
 *
 * 【たとえ話】
 * --resource = 「レストランのホール係として採用するので、
 *   注文受付・料理提供・お会計・予約管理などの仕事を
 *   全部セットで準備しますね」という設定
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan make:controller ExpenseController --resource

/**
 * → app/Http/Controllers/ExpenseController.php が作成されます
 *
 * 【作成後の確認】
 * VSCodeのエクスプローラーで
 * app/Http/Controllers/ExpenseController.php
 * が作成されているか確認してください。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. Route::resourceの設定】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Route::resource（ルートリソース）= CRUDに必要な7つのルートを
 * 1行で設定できる書き方
 *
 * 【たとえ話】
 * 通常のルート設定 = メニューに1品ずつ手書きで書く
 * Route::resource = 「定食セットメニュー」として一括で書く
 *
 * 【設定場所】
 * routes/web.php（ルーティングファイル）
 *
 * 【use文（ユーズ文）の追加が必要】
 * web.phpの上部に以下を追加する：
 * use App\Http\Controllers\ExpenseController;
 *
 * use文 = 「このファイルでExpenseControllerを使います」という宣言
 * 書かないと「ExpenseControllerって何？」というエラーになります
 */

// routes/web.phpに追加するコードです：

// ファイルの上部（他のuse文の近く）に追加：
// use App\Http\Controllers\ExpenseController;

// ルーティングの設定（既存のルートの後に追加）：
// Route::resource('expenses', ExpenseController::class);

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. Route::resourceで作られる7つのルート】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Route::resource('expenses', ExpenseController::class); の1行で
 * 以下の7つのルートが自動的に作られます。
 *
 * HTTPメソッド  URL                     メソッド   用途
 * ─────────────────────────────────────────────────────
 * GET          /expenses               index()    一覧表示
 * GET          /expenses/create        create()   新規作成フォーム
 * POST         /expenses               store()    新規作成処理
 * GET          /expenses/{expense}     show()     1件表示
 * GET          /expenses/{expense}/edit edit()    編集フォーム
 * PUT/PATCH    /expenses/{expense}     update()   編集処理
 * DELETE       /expenses/{expense}     destroy()  削除処理
 *
 * 【確認コマンド】
 * 設定が正しくできたか確認するには以下を実行します：
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan route:list --path=expenses

/**
 * → 7つのルートが表示されれば設定済みです
 *
 * 【--path=expenses の意味】
 * route:list = 全ルートの一覧を表示
 * --path=expenses = expensesに関係するものだけ絞り込む
 * （つけない場合は全ルートが表示されます）
 *
 * 【Route::resourceを使わない場合との比較】
 * Route::resourceなし：7行書く必要がある
 * Route::resourceあり：1行で済む
 *
 * 特定のルートだけ使いたい場合や、
 * 不要なルートがある場合はRoute::resourceを使わず
 * 個別に書くこともできます。
 * 例：Route::post('/expenses', [ExpenseController::class, 'store']);
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. モデル・コントローラー・web.phpの関係】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 今回編集した3つのファイルはこのように関連しています。
 *
 * 【レストランのたとえ話】
 *
 * web.php（メニュー表・道案内）
 * └─ 「/expenses にアクセスしたら ExpenseController に渡す」
 *    お客さん（ブラウザ）からの注文を受け付ける窓口
 *
 * ExpenseController（ウェイター）
 * └─ お客さんの注文を受けて、厨房（Expenseモデル）に伝える
 *    storeメソッドは「新しい支出データを保存して」という注文を処理
 *
 * Expense（モデル・厨房）
 * └─ 実際にデータベースに保存・取り出しをする
 *    ExpenseControllerから「保存して」と言われたら動く
 *
 * 【流れのイメージ】
 * ブラウザ → web.php（道案内）→ ExpenseController（処理）→ Expenseモデル（DB操作）
 *
 * ※ Expenseモデルのuse文もExpenseControllerに追加が必要です
 * use App\Models\Expense;
 * これを書かないと「Expenseって何？」というエラーになります
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. よくある質問（Q&A）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. --resource をつけないとどうなりますか？
 * A1. 空のコントローラーが作成されます。
 *     メソッドが1つもない状態なので、自分で全部書く必要があります。
 *     --resource をつけると7つのメソッドの雛形が入るので便利です。
 *
 * Q2. Route::resource の代わりに個別に書くこともできますか？
 * A2. できます。たとえば store() だけ使いたい場合はこう書けます：
 *     Route::post('/expenses', [ExpenseController::class, 'store']);
 *     ただし Route::resource の方が7つまとめて書けるので便利です。
 *
 * Q3. use 文を書かないとどうなりますか？
 * A3. 「Class 'Expense' not found」というエラーになります。
 *     PHPは「Expenseって何のこと？」と分からないためです。
 *     use 文は「このファイルでこのクラスを使います」という宣言です。
 *
 * Q4. artisan make:controller は何をしてくれますか？
 * A4. app/Http/Controllers/ の中にファイルを自動生成してくれます。
 *     自分でゼロからファイルを作るより、雛形があった方が間違いが少なくなります。
 *     Laravelの「artisan」コマンドはコントローラー以外にも
 *     モデル・マイグレーション・FormRequestなど様々なファイルを作れます。
 *
 * Q5. artisanコマンドはphpコンテナに入ってから実行する必要がありますか？
 * A5. 2つの方法があり、どちらも結果は同じです。
 *
 *     方法1：コンテナに入ってから実行
 *     docker exec -it laravel-practice-php-1 bash
 *     php artisan make:controller ExpenseController --resource
 *
 *     方法2：コンテナに入らずに実行（推奨）
 *     docker compose exec php php artisan make:controller ExpenseController --resource
 *
 *     方法2は1行で済むので効率的です。
 *     artisanコマンドはホストOS（自分のPC）から直接実行できず、
 *     必ずphpコンテナの中で実行する必要があります。
 *
 * Q6. 「docker compose exec app」と「docker compose exec php」の違いは何ですか？
 * A6. 「app」や「php」はdocker-compose.ymlで設定したサービス名です。
 *     KOHさんの環境ではサービス名が「php」なので、
 *     「docker compose exec php」が正しいコマンドです。
 *     教材に「app」と書いてある場合は「php」に読み替えてください。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📚 【用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * artisan（アーティザン）
 * └─ Laravelのコマンドラインツール。「職人」という意味
 *    ファイルの雛形作成、マイグレーション実行などができる
 *
 * make:controller（メイク コントローラー）
 * └─ コントローラーファイルを自動生成するartisanコマンド
 *
 * --resource（リソース）
 * └─ CRUDの7メソッド雛形付きでコントローラーを生成するオプション
 *
 * Route::resource（ルートリソース）
 * └─ CRUDに必要な7つのルートを一行で設定できる書き方
 *
 * use文（ユーズ文）
 * └─ 「このファイルで○○クラスを使います」という宣言
 *    書かないと「クラスが見つからない」エラーになる
 *
 * route:list（ルートリスト）
 * └─ 設定済みのルート一覧を表示するartisanコマンド
 *
 * docker compose exec php（ドッカー コンポーズ エグゼック ピーエイチピー）
 * └─ phpコンテナの中でコマンドを実行する
 *    artisanコマンドは必ずphpコンテナ内で実行する必要がある
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🎯 【次のステップ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step6-02では ExpenseController の作成とルーティング設定を行いました。
 *
 * Step6-03では「日本語エラーメッセージの設定」を学びます。
 * 現在のエラーメッセージは英語です。
 * 「The category field is required.」→「カテゴリは必須です。」
 * のように日本語に変える方法を学びます。
 *
 * 疑問があればいつでも質問してください！
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【第2部：追加Q&A（学習中に出た質問と回答）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q. phpコンテナに入ってからartisanコマンドを実行するのと、
 *    docker compose exec php php artisan ○○ の違いは何ですか？
 * A. 結果は同じです。どちらも「phpコンテナの中でartisanコマンドを実行する」
 *    という点では同じです。入るか入らないかの違いだけです。
 *    docker compose exec php php artisan ○○ の方が1行で済むので効率的です。
 *
 * Q. スクールの教材ではphpコンテナに入ってからコマンドをしていました。
 *    なぜコンテナに入らない方法をとらなかったと思いますか？
 * A. 2つの理由が考えられます。
 *    ①教育的な理由：「コンテナに入る→実行する→出る」という手順で
 *      「今自分がどこで作業しているか」を意識させやすいから。
 *    ②汎用性の理由：docker compose exec php の「php」はサービス名なので
 *      環境によって異なりますが、コンテナ名で入る方法はどの環境でも同じ手順。
 *
 * Q. Docker Desktopで環境構築して開発する場合、artisanの実行方法は変わりますか？
 * A. 基本的には変わりません。「phpコンテナの中でartisanを実行する」原則は同じです。
 *    コンテナ名がプロジェクトによって異なる可能性があるため、
 *    docker ps でコンテナ名を確認する習慣をつけておくと安心です。
 *
 * Q. docker compose exec php php artisan route:list --path=expenses の意味を教えてください。
 * A. 左から順に説明します。
 *    docker compose exec php = phpコンテナの中で次のコマンドを実行する
 *    php artisan = Laravelの便利ツール（artisan）をPHPで動かす
 *    route:list = 登録されているルートの一覧を表示する
 *    --path=expenses = expensesに関係するものだけ絞り込んで表示する
 *
 * Q. route:listの結果でPOSTのexpensesが表示されていれば「設定済み」ということですか？
 * A. その通りです。7行すべてが「設定済み」を意味しています。
 *    Route::resource の1行を書いただけで7つのルートが自動的に作られた
 *    ということをこの表示が証明しています。
 *
 * Q. 今回学んだことをまとめると？
 * A. ①--resourceオプションでコントローラーを作成すると、7つのルートを
 *      一括自動作成してくれるので便利。
 *    ②自分で限定したい場合や不要なルートがある場合はRoute::resourceを
 *      使わなくてもよい。
 *    ③artisanコマンドはphpコンテナに入らずに実行できる。
 *    ④モデル・コントローラー・web.phpの3つが連携して動く。
 */
