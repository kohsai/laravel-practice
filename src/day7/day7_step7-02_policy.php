<?php

/**
 * 📘 Day7 教材（Step7-02：Policyの作成と使い方）
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
 * Gateは AuthServiceProvider.php の boot() に書く、シンプルな認可の仕組みでした。
 *
 * 例えば、こんな書き方でした：
 *
 * Gate::define('update-expense', function (User $user, Expense $expense) {
 *     return $user->id === $expense->user_id;
 * });
 *
 * Gateの特徴：
 * - 1か所（AuthServiceProvider.php）にまとめて書く
 * - シンプルな条件に向いている
 * - 条件が少ないうちは管理しやすい
 *
 * 【Gateの限界】
 *
 * しかしGateには弱点があります。
 * 条件（認可ルール）が増えてくると、AuthServiceProvider.php が長くなりすぎます。
 *
 * 例えば、Expenseモデル（支出）に対して：
 * - 「見る」権限のルール
 * - 「作成する」権限のルール
 * - 「更新する」権限のルール
 * - 「削除する」権限のルール
 *
 * これを全部Gateで書くと、1つのファイルがどんどん膨らんでしまいます。
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
 * 会社には様々なルールがあります。
 *
 * ❌ Gateの場合（ルールが1か所に全部ある）：
 * 掲示板に「①経費申請は本人のみ見られる」「②経費申請は本人のみ編集できる」
 * 「③経費申請は本人のみ削除できる」「④注文書は営業部のみ見られる」...
 * → 掲示板が長くなりすぎて読めない
 *
 * ✅ Policyの場合（モデルごとにルール集がある）：
 * 「経費申請ルール集.pdf」= 経費に関するルールが全部まとまっている
 * 「注文書ルール集.pdf」  = 注文書に関するルールが全部まとまっている
 * → 「経費のルールは？」と聞かれたら、経費のルール集を開けばすぐわかる
 *
 * 【GateとPolicyの使い分け】
 *
 * | 場面 | 使うもの |
 * |------|---------|
 * | 特定のモデルに関係しないシンプルな条件 | Gate |
 * | 特定のモデルに対するCRUD権限 | Policy |
 * | 「管理者かどうか」などの単純な条件 | Gate |
 * | 「自分の投稿だけ編集できる」など | Policy |
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
 * 【artisanコマンドで作成する】
 *
 * Policy（ポリシー）ファイルは、artisan（アルチザン）コマンドで自動生成できます。
 *
 * artisan（アルチザン）＝ Laravelに最初から入っている「コマンドツール」
 * ものを作ったり、情報を調べたりするときに使います。
 *
 * 【コマンドの種類】
 *
 * ① Policyだけ作る場合：
 *    php artisan make:policy ExpensePolicy
 *
 * ② PolicyをModelと紐づけて作る場合（推奨）：
 *    php artisan make:policy ExpensePolicy --model=Expense
 *
 * 今回は ② の「--model=Expense」オプションをつけて作ります。
 * このオプションをつけると、よく使うメソッド（viewAll・view・create・update・delete）が
 * 最初から用意された状態で作成されます。
 *
 * 【たとえ話】
 * --model=Expense なし = 空白のノートを渡される
 * --model=Expense あり = 「見る・作る・更新・削除」のページタブがついたノートを渡される
 */

// 1. 以下のコマンドをコピーしてターミナルで実行してください：
// cd ~/venpro/laravel-practice/src/laravel
// php artisan make:policy ExpensePolicy --model=Expense

/**
 * → 実行後、以下のファイルが自動生成されます：
 *    app/Policies/ExpensePolicy.php
 *
 * ファイルの中身は次のセクションで確認します。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. 自動生成されたPolicyの中身を確認する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 自動生成されたファイルには以下のメソッドが入っています。
 *
 * 【メソッド一覧】
 *
 * | メソッド名 | 読み方 | 対応する操作 |
 * |-----------|--------|------------|
 * | viewAny   | ビューエニー | 一覧表示（例：全支出を見る） |
 * | view      | ビュー | 詳細表示（例：1つの支出を見る） |
 * | create    | クリエイト | 作成（例：支出を登録する） |
 * | update    | アップデート | 更新（例：支出を編集する） |
 * | delete    | デリート | 削除（例：支出を削除する） |
 * | restore   | リストア | 復元（ソフトデリート用） |
 * | forceDelete | フォースデリート | 完全削除（ソフトデリート用） |
 *
 * 【今回使うメソッド】
 * restore と forceDelete はソフトデリート（論理削除）の場合に使います。
 * 今回のExpenseモデルはソフトデリートを使っていないので、
 * この2つは削除してシンプルにします。
 *
 * 【Policyの書き方のルール】
 *
 * 各メソッドは「このユーザーはこの操作をしていいか？」を判断します。
 * 返すのは true（許可）または false（拒否）です。
 *
 * 引数（ざいりょう）として受け取るもの：
 * - $user = ログイン中のユーザー（User モデル）
 * - $expense = 操作対象の支出（Expense モデル）※ viewAny・createは不要
 *
 * 【実際のPolicyファイルの内容】
 *
 * 以下が今回使うExpensePolicyの内容です。
 * 自動生成されたファイルを、このように書き換えてください：
 */

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// app/Policies/ExpensePolicy.php に書く内容：
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

/**
 * （以下のコードをVSCodeでExpensePolicy.phpに貼り付けてください）
 */

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// ファイル: app/Policies/ExpensePolicy.php
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

// 【上書き内容はここから】
// <?php
//
// namespace App\Policies;
//
// use App\Models\Expense;
// use App\Models\User;
//
// class ExpensePolicy
// {
//     /**
//      * viewAny（ビューエニー）
//      * 「支出の一覧を見る」権限
//      * ログインしているユーザーなら誰でも見られる
//      */
//     public function viewAny(User $user): bool
//     {
//         return true; // ログインしていれば全員OK
//     }
//
//     /**
//      * view（ビュー）
//      * 「1件の支出の詳細を見る」権限
//      * 自分の支出だけ見られる
//      */
//     public function view(User $user, Expense $expense): bool
//     {
//         return $user->id === $expense->user_id;
//         // ログイン中のユーザーIDと、支出の所有者のIDが一致するか？
//     }
//
//     /**
//      * create（クリエイト）
//      * 「支出を新規作成する」権限
//      * ログインしているユーザーなら誰でも作れる
//      */
//     public function create(User $user): bool
//     {
//         return true; // ログインしていれば全員OK
//     }
//
//     /**
//      * update（アップデート）
//      * 「支出を編集する」権限
//      * 自分の支出だけ編集できる
//      */
//     public function update(User $user, Expense $expense): bool
//     {
//         return $user->id === $expense->user_id;
//         // ログイン中のユーザーIDと、支出の所有者のIDが一致するか？
//     }
//
//     /**
//      * delete（デリート）
//      * 「支出を削除する」権限
//      * 自分の支出だけ削除できる
//      */
//     public function delete(User $user, Expense $expense): bool
//     {
//         return $user->id === $expense->user_id;
//         // ログイン中のユーザーIDと、支出の所有者のIDが一致するか？
//     }
// }
// 【上書き内容ここまで】

/**
 * 【コードの読み方解説】
 *
 * return $user->id === $expense->user_id; の意味：
 *
 * $user->id       = ログイン中のユーザーのID（例：1）
 * ===             = 「完全に同じか？」という比較
 * $expense->user_id = この支出を作ったユーザーのID（例：1）
 *
 * つまり「この支出を作った人と、今操作しようとしている人は同じ人か？」を確認しています。
 *
 * 同じなら true（許可）→ 編集・削除できる
 * 違うなら false（拒否）→ 403エラー（権限なし）
 *
 * 【たとえ話】
 * 鍵と錠前のイメージです。
 * $user->id（ログイン中の人の鍵）と $expense->user_id（支出の錠前）が
 * ぴったり合ったとき（===）だけ、ドアが開く（true）。
 * 合わなければ（!==）、ドアは開かない（false）。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. PolicyをLaravelに認識させる（自動登録の確認）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【重要：Laravel 10の自動登録機能】
 *
 * Laravel 10では、Policyを作成すると「自動的に」LaravelがそのPolicyを
 * 認識してくれます。
 *
 * 条件：
 * - ModelクラスとPolicyクラスの名前が対応していること
 * - Expense モデル → ExpensePolicy（モデル名 + Policy）
 *
 * この命名規則に従っていれば、AuthServiceProvider.phpに何も書かなくてOKです。
 *
 * 【もし自動登録されない場合】
 *
 * 稀に自動登録がうまくいかない場合は、AuthServiceProvider.phpに
 * 手動で登録することもできます。今回は自動登録で進めます。
 *
 * 【確認方法】
 * artisanコマンドで確認できます：
 */

// 2. 以下のコマンドをコピーしてターミナルで実行してください：
// php artisan policy:list

/**
 * → Expense モデルに ExpensePolicy が紐づいているか確認できます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. ControllerでPolicyを使う（authorize）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【authorize（オーソライズ）とは？】
 * 読み方：オーソライズ
 * 意味：「許可を確認する」という命令
 *
 * Controllerのメソッド内で authorize() を呼ぶと：
 * → 許可されている場合：そのまま処理を続ける
 * → 許可されていない場合：自動的に403エラー（権限なし）のページを表示する
 *
 * 【たとえ話】
 * 警備員が入り口に立っているイメージです。
 * authorize() を呼ぶ = 警備員に「この人は入っていいですか？」と確認する
 * 許可 = 「どうぞ」（処理が続く）
 * 拒否 = 「入れません」（403ページが表示される）
 *
 * 【書き方】
 *
 * $this->authorize('ポリシーのメソッド名', 対象のモデル);
 *
 * 例：
 * $this->authorize('update', $expense);
 * → ExpensePolicy の update() メソッドを呼んで判断する
 *
 * 【実際のControllerへの追加】
 *
 * ExpenseController.php の edit・update・destroy メソッドに
 * authorize を追加します。
 */

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// app/Http/Controllers/ExpenseController.php の変更箇所：
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

/**
 * 【edit メソッド（編集画面を表示する）に追加する】
 *
 * 変更前：
 * public function edit(Expense $expense)
 * {
 *     return view('expenses.edit', compact('expense'));
 * }
 *
 * 変更後：
 * public function edit(Expense $expense)
 * {
 *     $this->authorize('update', $expense); // ← この1行を追加
 *     return view('expenses.edit', compact('expense'));
 * }
 *
 * 【update メソッド（編集内容を保存する）に追加する】
 *
 * 変更前：
 * public function update(StoreUserRequest $request, Expense $expense)
 * {
 *     $expense->update($request->validated());
 *     return redirect()->route('expenses.index');
 * }
 *
 * 変更後：
 * public function update(StoreUserRequest $request, Expense $expense)
 * {
 *     $this->authorize('update', $expense); // ← この1行を追加
 *     $expense->update($request->validated());
 *     return redirect()->route('expenses.index');
 * }
 *
 * 【destroy メソッド（削除する）に追加する】
 *
 * 変更前：
 * public function destroy(Expense $expense)
 * {
 *     $expense->delete();
 *     return redirect()->route('expenses.index');
 * }
 *
 * 変更後：
 * public function destroy(Expense $expense)
 * {
 *     $this->authorize('delete', $expense); // ← この1行を追加
 *     $expense->delete();
 *     return redirect()->route('expenses.index');
 * }
 */

/**
 * 【authorize の仕組み（内部で何が起きているか）】
 *
 * $this->authorize('update', $expense) を呼ぶと：
 *
 * 1. Laravelが「Expense モデルに紐づいたPolicy（ExpensePolicy）はどれ？」を確認
 * 2. ExpensePolicy の update() メソッドを呼ぶ
 * 3. 第2引数の $expense と、現在ログイン中のユーザーを自動で渡す
 * 4. update() が true を返せば → 処理を続ける
 * 5. update() が false を返せば → 403エラー（権限なし）のページを表示
 *
 * この流れを自動でやってくれるのが authorize() の便利なところです。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. BladeテンプレートでPolicyを使う（@can）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【@can（キャン）とは？】
 * 読み方：アットキャン
 * 意味：「〜できるか？」という条件分岐
 *
 * Step7-01では @cannot を使って「権限がない場合は非表示」にしました。
 * @can はその逆で「権限がある場合だけ表示」します。
 *
 * 【書き方】
 *
 * @can('ポリシーのメソッド名', $モデル変数)
 *     <!-- 権限がある場合だけ表示するHTML -->
 * @endcan
 *
 * 【使用例：支出一覧画面（expenses/index.blade.php）】
 *
 * 支出の一覧画面で、「自分の支出だけ編集・削除ボタンを表示する」例：
 *
 * @foreach($expenses as $expense)
 *     <tr>
 *         <td>{{ $expense->category }}</td>
 *         <td>{{ $expense->amount }}</td>
 *         <td>
 *             @can('update', $expense)
 *                 <a href="{{ route('expenses.edit', $expense) }}">編集</a>
 *             @endcan
 *
 *             @can('delete', $expense)
 *                 <form action="{{ route('expenses.destroy', $expense) }}" method="POST">
 *                     @csrf
 *                     @method('DELETE')
 *                     <button type="submit">削除</button>
 *                 </form>
 *             @endcan
 *         </td>
 *     </tr>
 * @endforeach
 *
 * 【@can と authorize() の違い】
 *
 * | | @can | authorize() |
 * |-|------|------------|
 * | 使う場所 | Bladeテンプレート | Controller |
 * | 目的 | ボタンの表示・非表示 | アクセス制御 |
 * | 権限なしの場合 | ボタンが表示されない | 403エラーページ |
 *
 * 【重要な考え方：表示制御だけでは不十分】
 *
 * @can でボタンを非表示にするだけでは不十分です。
 * URLを直接入力すれば、ボタンがなくてもアクセスできてしまいます。
 *
 * 例：AさんがBさんの支出ID=5の編集ページに直接アクセス
 * → /expenses/5/edit を直接URLに入力
 * → @can でボタンを隠していても、ページを開ける可能性がある
 *
 * だから：
 * - Controller の authorize() = 「本物のセキュリティ」（URLを直接入力されても防ぐ）
 * - Blade の @can = 「UX（使いやすさ）のため」（表示・非表示のコントロール）
 *
 * 両方を組み合わせて使うことが大切です。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【6. 動作確認】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【準備するもの】
 * テストのために「別ユーザーの支出」が必要です。
 * tinkerで2人目のユーザーと支出を作成します。
 */

// 3. 以下のコードをコピーしてtinkerで実行してください：
// php artisan tinker

// 4. tinker内で以下のコードをコピーして実行してください：
User::create([
    'name' => 'テストユーザーB',
    'email' => 'userb@example.com',
    'password' => bcrypt('password123')
]);

/**
 * → 2人目のユーザーが作成されます
 */

// 5. 続けて、2人目ユーザーの支出を作成（コピーして実行してください）：
$userB = User::where('email', 'userb@example.com')->first();
Expense::create([
    'user_id' => $userB->id,
    'category' => 'テスト支出',
    'amount' => 9999,
    'date' => '2026-04-01',
    'description' => 'Bさんの支出',
]);

/**
 * → Bさんの支出が作成されます
 *
 * 【確認手順】
 *
 * ① Aさん（自分のアカウント）でログイン
 * ② Bさんの支出の編集URLに直接アクセス
 *    例：http://localhost/expenses/【BさんのID】/edit
 * ③ 403エラーページが表示されることを確認
 *
 * 「403 This action is unauthorized.」が表示されれば成功です！
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【7. Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. GateとPolicyはどちらを使えばいい？
 * A1. モデルに対するCRUD操作の認可はPolicy、それ以外はGateが向いています。
 *     実務では「管理者かどうか」→Gate、「自分のデータか」→Policyという使い分けが多いです。
 *
 * Q2. authorize() を書かないとどうなる？
 * A2. URLを直接入力すれば他人のデータを操作できてしまいます。
 *     @can でボタンを非表示にしても、URLを直接入力されると突破されます。
 *     必ずController側にも authorize() を書いてください。
 *
 * Q3. Policyのメソッド名は自由に決められる？
 * A3. 自由に決められます。ただし viewAny・view・create・update・delete の5つを
 *     使うと、Laravelの規約に沿っているため読みやすくなります。
 *
 * Q4. return true; って何でもOKにしてしまって危なくない？
 * A4. 「ログインしていれば誰でもOK」という判断を意図的にしています。
 *     ログイン確認自体は middleware('auth') や @auth が担当しているので、
 *     「ログインしていない人は最初から弾かれている」という前提があります。
 *
 * Q5. $user->id === $expense->user_id の === は == と何が違う？
 * A5. === は「値も型も完全に同じ」という比較です。
 *     == は「値が同じ」という比較（型が違っても同じと判定する場合がある）。
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
 * └─ 「権限なし」のエラー。「このページを見る・操作する権限がありません」という意味
 *
 * viewAny（ビューエニー）
 * └─ Policyの「一覧表示」の権限を定義するメソッド名
 *
 * make:policy（メイクポリシー）
 * └─ artisanコマンドでPolicyファイルを生成する命令
 */
