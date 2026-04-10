<?php
/**
 * 📘 Day7 教材（Step7-02：Policyの作成と使い方）統合版
 *
 * この教材では「Policy（ポリシー）」という、より本格的な認可の仕組みを学びます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【Step7-01のおさらい】
 *
 * Step7-01では「Gate（ゲート）」を学びました。
 * Gateは AuthServiceProvider.php の boot() に書く、シンプルな認可の仕組みです。
 *
 * Gateの特徴：
 * - 1か所（AuthServiceProvider.php）にまとめて書く
 * - シンプルな条件に向いている
 * - 条件が増えると1つのファイルが膨らみすぎる（弱点）
 *
 * 【Policyとは？】
 *
 * Policy（ポリシー）＝「モデルに関する認可ルールを1つのクラスにまとめたもの」
 *
 * 読み方：ポリシー
 * 日常語での言い換え：「ルール集」「規則書」
 *
 * 【たとえ話：会社の規則書】
 *
 * ❌ Gateの場合：
 * 掲示板に全部のルールを貼る → 掲示板が長くなりすぎて読めない
 *
 * ✅ Policyの場合：
 * 「経費申請ルール集.pdf」= 経費に関するルールが全部まとまっている
 * → 「経費のルールは？」と聞かれたら、経費のルール集を開けばすぐわかる
 *
 * 【GateとPolicyの使い分け】
 *
 * 判断の基準は「特定のデータが必要かどうか」です。
 *
 * Gate  = 特定のデータを取得する必要がない場合
 *         例：「管理者かどうか」→ ユーザー情報だけで判断できる
 *
 * Policy = 特定のデータを取得する必要がある場合
 *          例：「自分の支出かどうか」→ Expenseのデータが必要
 *
 * 【今日学ぶこと】
 * 1. Policyファイルの作成（artisanコマンド）
 * 2. Policyのメソッドの書き方
 * 3. ControllerでのPolicyの使い方（authorize）
 * 4. BladeでのPolicyの使い方（@can）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. Policyファイルの作成】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * PolicyをModelと紐づけて作る場合（推奨）：
 * php artisan make:policy ExpensePolicy --model=Expense
 *
 * --model=Expense をつけると、よく使うメソッド（viewAny・view・create・update・delete）が
 * 最初から用意された状態で作成されます。
 *
 * リソースルーティングが7つのルートを自動生成するのと同じイメージです。
 *
 * → 実行後、以下のファイルが自動生成されます：
 *    app/Policies/ExpensePolicy.php
 */

// 以下のコマンドをターミナルで実行してください（src/laravelディレクトリ内で）：
// php artisan make:policy ExpensePolicy --model=Expense

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. ExpensePolicyの内容】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 自動生成されたファイルを以下の内容に書き換えてください。
 *
 * 【メソッド一覧】
 *
 * | メソッド名 | 読み方 | 対応する操作 |
 * |-----------|--------|------------|
 * | viewAny   | ビューエニー | 一覧表示 |
 * | view      | ビュー | 詳細表示 |
 * | create    | クリエイト | 作成 |
 * | update    | アップデート | 更新 |
 * | delete    | デリート | 削除 |
 *
 * ※ restore・forceDelete はソフトデリート用なので今回は削除します
 *
 * 【Policyの書き方のルール】
 *
 * 各メソッドは「このユーザーはこの操作をしていいか？」を判断します。
 * 返すのは true（許可）または false（拒否）です。
 *
 * 【コードの読み方】
 *
 * return $user->id === $expense->user_id; の意味：
 *
 * $user->id         = ログイン中のユーザーのID（例：2）
 * ===               = 「値も型も完全に同じか？」という比較（==より厳密）
 * $expense->user_id = この支出を作ったユーザーのID（例：2）
 *
 * 同じなら true（許可）→ 編集・削除できる
 * 違うなら false（拒否）→ 403エラー
 *
 * たとえ話：
 * $user->id（ログイン中の人の鍵）と $expense->user_id（支出の錠前）が
 * ぴったり合ったとき（===）だけ、ドアが開く（true）。
 */

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// ファイル: app/Policies/ExpensePolicy.php の内容
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// （以下を ExpensePolicy.php に全選択して貼り付けてください）

/**
 * ↓ ここから ExpensePolicy.php に貼り付ける内容
 *
 * <?php
 *
 * namespace App\Policies;
 *
 * use App\Models\Expense;
 * use App\Models\User;
 *
 * class ExpensePolicy
 * {
 *     // viewAny（ビューエニー）= 一覧表示の権限
 *     // ログインしていれば誰でも見られる
 *     public function viewAny(User $user): bool
 *     {
 *         return true;
 *     }
 *
 *     // view（ビュー）= 詳細表示の権限
 *     // 自分の支出だけ見られる
 *     public function view(User $user, Expense $expense): bool
 *     {
 *         return $user->id === $expense->user_id;
 *     }
 *
 *     // create（クリエイト）= 作成の権限
 *     // ログインしていれば誰でも作れる
 *     public function create(User $user): bool
 *     {
 *         return true;
 *     }
 *
 *     // update（アップデート）= 更新の権限
 *     // 自分の支出だけ編集できる
 *     public function update(User $user, Expense $expense): bool
 *     {
 *         return $user->id === $expense->user_id;
 *     }
 *
 *     // delete（デリート）= 削除の権限
 *     // 自分の支出だけ削除できる
 *     public function delete(User $user, Expense $expense): bool
 *     {
 *         return $user->id === $expense->user_id;
 *     }
 * }
 * ↑ ここまで
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. PolicyをLaravelに認識させる（自動登録）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【Laravel 10の自動登録機能】
 *
 * Laravel 10では、命名規則に従っていれば自動でPolicyが認識されます。
 *
 * Expense モデル → ExpensePolicy（モデル名 + Policy）
 *
 * この命名規則に従っていれば、AuthServiceProvider.phpに何も書かなくてOKです。
 *
 * 【注意】
 * php artisan policy:list というコマンドはLaravel 11以降のみ使えます。
 * Laravel 10では使えないため、実際に動作確認（【6】）で確認します。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. ControllerでPolicyを使う（authorize）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【メソッドとは何か】
 *
 * メソッド = Controllerの中に書かれた「機能のかたまり」です。
 *
 * ExpenseControllerの場合：
 * - index()   = 一覧を表示する機能
 * - create()  = 作成フォームを表示する機能
 * - store()   = 保存する機能
 * - edit()    = 編集フォームを表示する機能
 * - update()  = 更新する機能
 * - destroy() = 削除する機能
 *
 * 【authorize（オーソライズ）とは？】
 *
 * メソッドの入り口に「警備員」を置くイメージです。
 *
 * $this->authorize('update', $expense);
 *
 * - 第1引数 'update' = ExpensePolicyのどのメソッドで判断するか
 * - 第2引数 $expense = 判断に使うデータ（どの支出か）
 *
 * 許可 → 処理を続ける
 * 拒否 → 自動的に403エラーページを表示
 *
 * 【authorizeの内部の流れ】
 *
 * $this->authorize('update', $expense) を呼ぶと：
 *
 * 1. Laravelが「$expenseはExpenseモデル → ExpensePolicyを使おう」と自動判断
 * 2. ExpensePolicyのupdate()を呼ぶ
 * 3. ログイン中のユーザーを自動で取得して渡す（自分で取得する必要がない）
 * 4. update()がtrueなら処理を続ける、falseなら403エラー
 *
 * 【役割分担のまとめ】
 *
 * Controller  = 「ここに警備が必要」と宣言する
 * Policy      = 「誰を通すかのルール集」
 * Laravel本体 = 実際に立っている警備員（ルール集を見ながら判断・実行）
 *
 * 【ExpenseControllerの完成形】
 * （以下が今回実装したExpenseController.phpの内容です）
 */

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// ファイル: app/Http/Controllers/ExpenseController.php の内容（参考）
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

/**
 * <?php
 *
 * namespace App\Http\Controllers;
 *
 * use App\Models\Expense;
 * use App\Http\Requests\StoreUserRequest;
 *
 * class ExpenseController extends Controller
 * {
 *     /**
 *      * 一覧表示
 *      * 全支出データを取得してビューに渡す（未実装・Step7-04で実装予定）
 *      */
 *     public function index()
 *     {
 *         //
 *     }
 *
 *     /**
 *      * 作成フォーム表示
 *      * 支出登録フォームのページを表示する
 *      */
 *     public function create()
 *     {
 *         return view('expenses.create');
 *         // view('expenses.create') = resources/views/expenses/create.blade.php を表示する
 *     }
 *
 *     /**
 *      * 保存処理
 *      * フォームの入力データをバリデーション済みで受け取り、DBに保存する
 *      */
 *     public function store(StoreUserRequest $request)
 *     {
 *         Expense::create(array_merge($request->validated(), ['user_id' => auth()->id()]));
 *         // Expense::create()           = expensesテーブルに新しいレコードを追加する
 *         // $request->validated()       = バリデーション済みの入力データ
 *         // array_merge()               = 2つの配列を1つにまとめる
 *         // ['user_id' => auth()->id()] = ログイン中のユーザーIDをuser_idとして追加する
 *         return redirect('/expenses');
 *         // 保存後、支出一覧ページにリダイレクト（移動）する
 *     }
 *
 *     /**
 *      * 詳細表示（未実装）
 *      */
 *     public function show(string $id)
 *     {
 *         //
 *     }
 *
 *     /**
 *      * 編集フォーム表示
 *      * 指定した支出の編集フォームを表示する
 *      */
 *     public function edit(Expense $expense)
 *     {
 *         $this->authorize('update', $expense);
 *         // $this->authorize() = 権限を確認する（警備員に「入っていいか？」を聞く）
 *         // 'update'           = ExpensePolicyのupdate()メソッドで判断する
 *         // $expense           = 判断対象のデータ（どの支出か）
 *         // → trueなら次へ進む、falseなら403エラー
 *         return view('expenses.edit', compact('expense'));
 *         // compact('expense') = $expense変数をビューに渡す
 *     }
 *
 *     /**
 *      * 更新処理
 *      * フォームの入力データをバリデーション済みで受け取り、DBを更新する
 *      */
 *     public function update(StoreUserRequest $request, Expense $expense)
 *     {
 *         $this->authorize('update', $expense);
 *         // edit()と別にauthorizeを書く理由：
 *         // URLを直接入力された場合もここで防ぐ（editのauthorizeだけでは不十分）
 *         $expense->update($request->validated());
 *         // $expense->update() = 対象の支出レコードをバリデーション済みデータで上書きする
 *         return redirect()->route('expenses.index');
 *         // 更新後、支出一覧ページにリダイレクトする
 *     }
 *
 *     /**
 *      * 削除処理
 *      * 指定した支出をDBから削除する
 *      */
 *     public function destroy(Expense $expense)
 *     {
 *         $this->authorize('delete', $expense);
 *         // 'delete' = ExpensePolicyのdelete()メソッドで判断する
 *         // → 自分の支出でなければ403エラー
 *         $expense->delete();
 *         // 対象の支出レコードをDBから削除する
 *         return redirect()->route('expenses.index');
 *         // 削除後、支出一覧ページにリダイレクトする
 *     }
 * }
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. BladeテンプレートでPolicyを使う（@can）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【@can（キャン）とは？】
 * 「権限がある場合だけ表示」する条件分岐です。
 * Step7-01の @cannot（権限がない場合だけ表示）の逆です。
 *
 * 【@can と authorize() の違い】
 *
 * | | @can | authorize() |
 * |-|------|------------|
 * | 使う場所 | Bladeテンプレート | Controller |
 * | 目的 | ボタンの表示・非表示 | アクセス制御 |
 * | 権限なしの場合 | ボタンが表示されない | 403エラーページ |
 *
 * 【重要：表示制御だけでは不十分】
 *
 * @can でボタンを非表示にするだけでは不十分です。
 * URLを直接入力すれば、ボタンがなくてもアクセスできてしまいます。
 *
 * - Controller の authorize() = 「本物のセキュリティ」
 * - Blade の @can = 「UX（使いやすさ）のため」
 *
 * 両方を組み合わせて使うことが大切です。
 *
 * 【実装はStep7-04で行います】
 *
 * expenses/index.blade.phpがまだ存在しないため、
 * @canの実装はStep7-04（認可と多対多の組み合わせ）で行います。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【6. 動作確認】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【事前準備：tinkerの起動】
 *
 * ⚠️ tinkerはDockerコンテナ内で実行する必要があります。
 *    また初回は以下のコマンドでディレクトリを作成してください：
 *
 *    docker compose exec -u root php mkdir -p /.config/psysh
 *    docker compose exec -u root php chmod 777 /.config/psysh
 *
 * tinkerの起動（laravel-practiceディレクトリで実行）：
 */

// docker compose exec php php artisan tinker

/**
 * 【テストユーザーBの作成】
 */

// tinker内で以下を実行してください：
User::create([
    'name' => 'テストユーザーB',
    'email' => 'userb@example.com',
    'password' => bcrypt('password123')
]);

/**
 * → テストユーザーBが作成されます（IDを確認してください）
 */

// 続けてBさんの支出を作成してください：
$userB = User::where('email', 'userb@example.com')->first();
Expense::create([
    'user_id' => $userB->id,
    'category' => 'テスト支出',
    'amount' => 9999,
    'spent_at' => '2026-04-01',
    'description' => 'Bさんの支出',
]);

/**
 * → Bさんの支出が作成されます（IDを確認してください）
 *
 * 【確認手順】
 *
 * ① 自分のアカウントでログイン
 * ② Bさんの支出の編集URLに直接アクセス
 *    例：http://localhost:8080/expenses/【BさんのexpenseのID】/edit
 * ③ 「403 この行為は許可されていません。」が表示されれば成功
 *
 * 【動作確認後】
 * テストユーザーBはphpMyAdminから削除してOKです。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【追加Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. GateとPolicyの使い分けは？
 * A1. 「判断に特定のデータが必要かどうか」が分かれ目です。
 *     ユーザー情報だけで判断できる → Gate
 *     特定のモデルのデータが必要 → Policy
 *     条件の複雑さは関係ありません。
 *
 * Q2. authorize() を書かないとどうなる？
 * A2. URLを直接入力すれば他人のデータを操作できてしまいます。
 *     @can でボタンを非表示にしても防げません。
 *     必ずController側にも authorize() を書いてください。
 *
 * Q3. edit()とupdate()の両方にauthorize()が必要な理由は？
 * A3. edit()だけに書いた場合、フォームのsubmit先（update）には
 *     直接POSTリクエストを送ることができます。
 *     両方に書くことで確実に防げます。
 *
 * Q4. store()でauth()->id()を使う理由は？
 * A4. Day6ではuser_idを1固定にしていました。
 *     Day7でPolicyを学んだタイミングで、ログイン中のユーザーIDを
 *     自動取得する形に修正しました。
 *     auth()->id() = 現在ログイン中のユーザーのIDを返します。
 *
 * Q5. return true; って何でもOKにしてしまって危なくない？
 * A5. 「ログインしていれば誰でもOK」という意図的な判断です。
 *     ログインしていない人は middleware('auth') が最初から弾いているので、
 *     Policyまで到達するのはログイン済みユーザーだけです。
 *
 * Q6. === と == の違いは？
 * A6. === は「値も型も完全に同じ」、== は「値が同じ」という比較です。
 *     認可のような重要な判断では === を使う方が安全です。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Policy（ポリシー）
 * └─ 特定のモデルに対する認可ルールをまとめたクラス
 *
 * authorize()（オーソライズ）
 * └─ Controllerで認可を確認するメソッド。権限なしは自動で403エラー
 *
 * @can（キャン）
 * └─ Bladeで「権限がある場合だけ表示」する命令
 *
 * @cannot（キャンノット）
 * └─ Bladeで「権限がない場合だけ表示」する命令
 *
 * 403エラー
 * └─ 「権限なし」のエラー。「この操作をする権限がありません」という意味
 *
 * auth()->id()（オース・アイディー）
 * └─ 現在ログイン中のユーザーのIDを取得するメソッド
 *
 * make:policy（メイクポリシー）
 * └─ artisanコマンドでPolicyファイルを生成する命令
 */