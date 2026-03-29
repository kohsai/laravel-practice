<?php

/**
 * 📘 Day6 教材（Step6-05：FormRequestの作成と使い方）
 *
 * この教材では「バリデーションを専用クラスに切り出す方法」を学びます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step6-02では、バリデーションをコントローラーの中に書きました。
 *
 * 【Step6-02のコード（おさらい）】
 * public function store(Request $request)
 * {
 *     $request->validate([
 *         'category'    => 'required|in:食費,交通費,娯楽費,その他',
 *         'amount'      => 'required|integer|min:1|max:9999999',
 *         'description' => 'nullable|string|max:200',
 *         'spent_at'    => 'required|date|before_or_equal:today',
 *     ]);
 *
 *     Expense::create($request->all());
 *     return redirect('/expenses');
 * }
 *
 * この書き方でも動きますが、1つ問題があります。
 * バリデーションルールが増えると、コントローラーが長くなってしまうのです。
 *
 * 【FormRequestとは？】
 * FormRequest（フォームリクエスト）= バリデーション専用のクラス（設計図）
 * コントローラーからバリデーションを切り出して、別のファイルに書く仕組みです。
 *
 * 【今日学ぶこと】
 * 1. FormRequestの作成方法（Artisanコマンド）
 * 2. FormRequestの中身（rules・authorize）
 * 3. コントローラーでの使い方
 * 4. カスタムエラーメッセージの設定
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. FormRequestを作成する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * FormRequestはArtisan（アーティサン）コマンドで作成します。
 *
 * 【Artisanとは？】
 * Artisan（アーティサン）= Laravelに最初から入っている「コマンドラインツール」
 * ターミナルでコマンドを打つと、ファイルを自動的に作ってくれます。
 *
 * 【コマンドの意味】
 * docker compose exec php php artisan make:request StoreUserRequest
 *
 * - docker compose exec php = phpコンテナの中でコマンドを実行する
 * - php artisan = LaravelのArtisanツールを使う
 * - make:request = 「FormRequestを作って」という命令
 * - StoreUserRequest = 作るファイルの名前
 *
 * 【ファイル名の命名ルール】
 * 「何をする（Store/Update/Delete）+ 何の（User/Post/Expense）+ Request」
 * 例：
 * - StoreExpenseRequest  = 支出を新規作成するときのバリデーション（本来の名前）
 * - StoreUserRequest     = ユーザーを新規作成するときのバリデーション
 * - UpdateExpenseRequest = 支出を更新するときのバリデーション
 *
 * 【注意】
 * 今回は StoreUserRequest という名前で作成しましたが、
 * 本来は支出（Expense）を扱うので StoreExpenseRequest が正しい命名です。
 * 動作には影響ありませんが、実務では扱うデータに合わせた名前をつけます。
 */

// 【ターミナルで実行してください】以下のコマンドをコピーして実行してください：
// docker compose exec php php artisan make:request StoreUserRequest

/**
 * → app/Http/Requests/StoreUserRequest.php が作成されます
 *
 * 【確認】
 * VSCodeのエクスプローラーで app/Http/Requests/ フォルダを確認
 * StoreUserRequest.php があればOKです
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. FormRequestの中身を確認する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 作成したファイルを開くと、以下のような内容が書かれています。
 *
 * 【自動生成されたコード】
 *
 * class StoreUserRequest extends FormRequest
 * {
 *     public function authorize(): bool
 *     {
 *         return false;  ← ここが重要！最初は false になっています
 *     }
 *
 *     public function rules(): array
 *     {
 *         return [
 *             // ここにルールを書く
 *         ];
 *     }
 * }
 *
 * 【2つのメソッドの説明】
 *
 * ① authorize()（オーソライズ）= 「この操作を許可するか？」を決める
 *   - true  = 「許可する」（誰でも使える）
 *   - false = 「許可しない」（403エラーになる）
 *   - 最初は false になっているので、必ず true に変える必要があります
 *
 * ② rules()（ルールズ）= 「バリデーションのルール」を返す
 *   - コントローラーで書いていた $request->validate([...]) の中身を
 *     ここに移動させます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. FormRequestを編集する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * app/Http/Requests/StoreUserRequest.php を以下のように編集します。
 *
 * 【重要な変更点】
 * 1. authorize() を false → true に変える
 * 2. rules() にバリデーションルールを書く
 * 3. messages() でエラーメッセージを日本語にする
 *
 * 【StoreUserRequest.phpの完成形】
 *
 * -------------------------------------------------------
 * namespace App\Http\Requests;
 *
 * use Illuminate\Foundation\Http\FormRequest;
 *
 * class StoreUserRequest extends FormRequest
 * {
 *     public function authorize(): bool
 *     {
 *         return true;
 *     }
 *
 *     public function rules(): array
 *     {
 *         return [
 *             'category'    => 'required|in:食費,交通費,娯楽費,その他',
 *             'amount'      => 'required|integer|min:1|max:9999999',
 *             'description' => 'nullable|string|max:200',
 *             'spent_at'    => 'required|date|before_or_equal:today',
 *         ];
 *     }
 *
 *     public function messages(): array
 *     {
 *         return [
 *             'category.required' => 'カテゴリは必ず選択してください',
 *             'category.in'       => 'カテゴリは食費・交通費・娯楽費・その他から選んでください',
 *             'amount.required'   => '金額は必ず入力してください',
 *             'amount.integer'    => '金額は数字で入力してください',
 *             'amount.min'        => '金額は1円以上で入力してください',
 *             'amount.max'        => '金額は9,999,999円以下で入力してください',
 *             'description.max'   => '説明は200文字以内で入力してください',
 *             'spent_at.required' => '日付は必ず入力してください',
 *             'spent_at.date'     => '日付の形式が正しくありません',
 *             'spent_at.before_or_equal' => '日付は今日以前の日付を入力してください',
 *         ];
 *     }
 * }
 * -------------------------------------------------------
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. コントローラーを修正する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * FormRequestを使うように、ExpenseController を修正します。
 *
 * 【変更の手順】
 * 1. use Illuminate\Http\Request; を削除
 * 2. use App\Http\Requests\StoreUserRequest; を追加
 * 3. store()メソッドの引数を Request → StoreUserRequest に変更
 * 4. $request->validate([...]) の部分を削除
 *
 * 【処理の流れ】
 * フォームから「登録する」を押すと、フォームのデータがLaravelに届きます。
 * Laravelは store() の引数に StoreUserRequest が書かれているのを見て、
 * 「store()を実行する前に、まずStoreUserRequestに確認させよう」と判断します。
 *
 * StoreUserRequestは以下の順番で動きます：
 * 1. authorize() を見る → true なので通過
 * 2. rules() のルールでデータをチェックする
 *
 * チェック失敗 → store()は実行されず、フォームに戻ってエラーを表示
 * チェック合格 → store()の中身が実行されてデータが保存される
 *
 * 【修正前のExpenseController】
 *
 * use Illuminate\Http\Request;
 *
 * public function store(Request $request)
 * {
 *     $request->validate([
 *         'category'    => 'required|in:食費,交通費,娯楽費,その他',
 *         'amount'      => 'required|integer|min:1|max:9999999',
 *         'description' => 'nullable|string|max:200',
 *         'spent_at'    => 'required|date|before_or_equal:today',
 *     ]);
 *
 *     Expense::create($request->all());
 *     return redirect('/expenses');
 * }
 *
 * 【修正後のExpenseController】
 *
 * use App\Http\Requests\StoreUserRequest;  ← use文を変更
 *
 * public function store(StoreUserRequest $request)  ← 引数の型を変更
 * {
 *     // $request->validate(...) は削除
 *     // バリデーションはStoreUserRequestが自動でやってくれる！
 *
 *     Expense::create(array_merge($request->all(), ['user_id' => 1]));
 *     return redirect('/expenses');
 * }
 *
 * 【user_id について】
 * expenses テーブルには user_id カラムがあり、値が必須です。
 * フォームには user_id の入力欄がないため、コード側で補う必要があります。
 * 今は動作確認のため固定値（1）を入れています。
 * 将来ログイン機能と連携するときは ['user_id' => auth()->id()] に変更します。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. Step6-02との比較まとめ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【コントローラー直書き（Step6-02の方法）】
 * ✅ シンプルで分かりやすい
 * ✅ ファイルが1つで済む
 * ❌ コントローラーが長くなる
 * ❌ 同じルールを複数の場所で使う場合に重複が出る
 * → 小さいプロジェクト・シンプルなフォームに向いている
 *
 * 【FormRequest（今日の方法）】
 * ✅ コントローラーがスッキリする
 * ✅ バリデーションルールを1か所で管理できる
 * ✅ 同じルールを複数のコントローラーで使い回せる
 * ✅ メッセージの日本語化もまとめて管理できる
 * ❌ ファイルが1つ増える
 * → 本番プロジェクト・フォームが複雑になってきたら使う
 *
 * 実務では FormRequest を使うのが一般的です。
 * GodeVen開発では FormRequest を積極的に使っていきましょう。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【6. よくあるミス・注意点】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * ❌ よくあるミス1：authorize() を true に変え忘れる
 *
 * 症状：「403 This action is unauthorized.」エラー
 * 原因：FormRequestを作ったとき、authorize() は最初 false になっています。
 *       false のままだと「この操作は許可しない」という状態なので、
 *       フォームを送信するたびに403エラーになります。
 * 対処：authorize() の return false; を return true; に変える
 *
 * ❌ よくあるミス2：use文を追加し忘れる
 *
 * 症状：「Class 'App\Http\Controllers\StoreUserRequest' not found」エラー
 * 原因：PHPは「StoreUserRequest」という名前だけではどこにあるクラスか分かりません。
 *       use文は「このファイルはapp/Http/Requests/にあります」と教える役割です。
 *       use文がないと「StoreUserRequestって何？」とエラーになります。
 * 対処：コントローラーの先頭に以下を追加する
 *       use App\Http\Requests\StoreUserRequest;
 *
 * ❌ よくあるミス3：引数の型を変更し忘れる
 *
 * 症状：バリデーションが実行されない（エラーなくデータが保存されてしまう）
 * 原因：引数が store(Request $request) のままだと、
 *       LaravelはFormRequestを使うとは認識せず、バリデーションをスキップします。
 *       引数を StoreUserRequest に変えることで初めて「FormRequestを使う」と認識します。
 * 対処：store(Request $request) → store(StoreUserRequest $request) に変更する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【7. 用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * FormRequest（フォームリクエスト）
 * └─ バリデーション専用のクラス。コントローラーから切り出して管理する
 *
 * authorize()（オーソライズ）
 * └─ 「この操作を許可するか」を決めるメソッド
 *    true = 許可 / false = 拒否（403エラー）
 *
 * rules()（ルールズ）
 * └─ バリデーションのルールを配列で返すメソッド
 *
 * messages()（メッセージズ）
 * └─ カスタムエラーメッセージを配列で返すメソッド（省略可能）
 *
 * Artisan（アーティサン）
 * └─ Laravelのコマンドラインツール。ファイル生成などに使う
 *
 * make:request（メイクリクエスト）
 * └─ FormRequestファイルを作成するArtisanコマンド
 *
 * 403エラー（よんひゃくさんエラー）
 * └─ 「この操作は許可されていません」というエラー
 *    authorize() が false のときに発生する
 *
 * array_merge()（アレイマージ）
 * └─ 2つの配列を1つにまとめる関数
 *    $request->all() のデータに ['user_id' => 1] を追加するために使う
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【よくある質問（Q&A）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. FormRequestはどこに保存されますか？
 * A1. app/Http/Requests/ フォルダに保存されます。
 *     このフォルダはArtisanコマンドで初めてFormRequestを作ると自動的に作成されます。
 *
 * Q2. authorize() はいつ false にするの？
 * A2. ログインしているユーザーだけが使えるフォームを作るときに活用します。
 *     例えば「自分の投稿だけ編集できる」という制御に使います。
 *     今の段階では true にしておけば大丈夫です。
 *
 * Q3. messages() は必ず書かないといけませんか？
 * A3. 書かなくてもバリデーションは動きます。
 *     書かない場合はLaravelのデフォルトメッセージ（英語）が表示されます。
 *     日本語化したい場合は messages() を追加します。
 *
 * Q4. コントローラーの use Illuminate\Http\Request; は削除していいですか？
 * A4. store()メソッドでしか Request を使っていないなら削除しても構いません。
 *     ただし他のメソッドで Request を使っている場合は残してください。
 *     迷ったら残しておく方が安全です。
 *     今回のExpenseControllerでは update() メソッドの引数から Request を
 *     削除したため、use Illuminate\Http\Request; も削除しました。
 *
 * Q5. UpdateExpenseRequest など更新用も同じように作れますか？
 * A5. はい。docker compose exec php php artisan make:request UpdateExpenseRequest
 *     で作れます。update()メソッドには UpdateExpenseRequest を使うのが一般的です。
 *
 * Q6. artisanコマンドはどこで実行しますか？
 * A6. ホストOS（自分のPC）から直接 php artisan を実行することはできません。
 *     PHPの実行環境はDockerコンテナの中にあるためです。
 *     docker compose exec php php artisan ○○ の形式で実行します。
 *     実行場所は ~/venpro/laravel-practice/（docker-compose.ymlがある場所）です。
 *
 * Q7. user_id を固定値（1）にしているのはなぜですか？
 * A7. expenses テーブルの user_id カラムは「必須」になっているため、
 *     値なしでは保存できません。
 *     フォームには user_id の入力欄がないので、コード側で補う必要があります。
 *     今は動作確認のため固定値（1）を入れています。
 *     将来ログイン機能と連携するときは auth()->id() に変更します。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【第2部：学習中に出た質問と回答】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q. 教材のサンプルコード（name・email・password）と
 *    実際のアプリ（category・amount・description・spent_at）が違う。
 *    なぜ教材通りに進めると内容を変える必要がある？
 * A. 教材は「FormRequestの書き方を学ぶ」ための汎用的なサンプルで作成しました。
 *    実際のアプリに合わせて rules() と messages() の中身を書き換える必要があります。
 *    「書き方・仕組みを学ぶ部分」と「自分のアプリに合わせて変える部分」を
 *    意識して進めることが大切です。
 *
 * Q. artisanコマンドはどのディレクトリで実行するか？
 *    phpコンテナに入ってから実行するのとの違いは？
 * A. 実行場所は ~/venpro/laravel-practice/（docker-compose.ymlがある場所）です。
 *    コンテナに入らずに実行する方法（推奨）：
 *    docker compose exec php php artisan make:request StoreUserRequest
 *    コンテナに入ってから実行する方法：
 *    docker exec -it laravel-practice-php-1 bash
 *    php artisan make:request StoreUserRequest
 *    どちらも結果は同じですが、1行で済む前者の方が効率的です。
 *
 * Q. フォームを送信したとき、validate() が store() にないのにバリデーションが
 *    実行されるのはなぜか？
 * A. Laravelは store() の引数の型が FormRequest のサブクラスだと認識すると、
 *    store() の中身を実行する前に自動的にバリデーションを実行します。
 *    引数を StoreUserRequest に変えるだけで、Laravelが自動的に処理してくれます。
 *
 * Q. 正常なデータを送信したら「Field 'user_id' doesn't have a default value」
 *    エラーが出た。なぜ？
 * A. expenses テーブルの user_id カラムは値が必須の設定になっています。
 *    フォームには user_id の入力欄がないため、INSERT時に値がなくてエラーになります。
 *    array_merge($request->all(), ['user_id' => 1]) で user_id を補って解決しました。
 *    将来ログイン機能と連携するときは ['user_id' => auth()->id()] に変更します。
 */
