<?php

/**
 * 📘 Day8 教材（Step8-02：ファイルアップロードの実装 - 支出に画像を添付）
 *
 * この教材では「フォームから画像を受け取り、サーバーに保存する」実装を学びます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step8-01で「ファイルの保存場所（storage）」の仕組みを学びました。
 * Step8-02では実際に「画像を受け取って保存する」コードを実装します。
 *
 * 【今回実装する機能】
 * 支出（Expense）を登録するとき、画像も一緒に添付できるようにする
 *
 * 例：「ランチ代 800円」という支出を登録するとき、
 *     レシートの写真も一緒に保存できるようにする
 *
 * 【作業の全体像】
 *
 * 1. マイグレーション作成    → expensesテーブルに image_path カラムを追加する
 * 2. Expense モデルの修正    → image_path を保存できるように設定する
 * 3. フォームの修正          → create.blade.php にファイル選択欄を追加する
 * 4. コントローラーの修正    → store() に画像を保存する処理を追加する
 * 5. 一覧ページの修正        → index.blade.php に画像を表示する欄を追加する
 *
 * 【現在の expensesテーブルのカラム（Step8-01で確認済み）】
 * id / user_id / category / amount / description / spent_at
 * ↑ image_path がまだない → 今回追加します
 *
 * 【.env の確認（重要）】
 * Storage::url() は APP_URL を基にURLを生成します。
 * APP_URL にポート番号がないと画像が表示されません。
 * .env の APP_URL が以下のようになっているか確認してください：
 * APP_URL=http://localhost:8080
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. マイグレーションの作成】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * まず、expensesテーブルに「画像のパス（保存場所の住所）」を
 * 保存するための image_path カラムを追加します。
 *
 * 【たとえ話】
 * 今の支出記録ノート（expensesテーブル）には
 * 「カテゴリ・金額・説明・日付」の欄しかありません。
 * そこに「画像のファイル名」を書く欄を追加するイメージです。
 *
 * 【マイグレーションとは？（おさらい）】
 * テーブルの構造を変更する「設計書」です。
 * コマンドで実行すると、データベースに変更が反映されます。
 *
 * 【新規作成 vs カラム追加】
 * これまで学んだのは「テーブルを新しく作る」マイグレーションでした。
 * 今回は「既存のテーブルにカラムを追加する」マイグレーションです。
 * ファイルの中身が少し違います（down() の書き方が変わります）。
 */

// 以下のコマンドでマイグレーションファイルを作成してください：
// docker compose exec php php artisan make:migration add_image_path_to_expenses_table --table=expenses

/**
 * → ファイルが作成されます：
 *   database/migrations/xxxx_xx_xx_xxxxxx_add_image_path_to_expenses_table.php
 *
 * 【コマンドの読み方】
 * make:migration（メイク・マイグレーション） = マイグレーションファイルを作る
 * add_image_path_to_expenses_table = ファイル名（後から見てわかりやすい名前）
 * --table=expenses（テーブル・イコール・expenses） = 「expensesテーブルを変更する」という指定
 * この --table オプションをつけると、中身が「変更用」のテンプレートで作られます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. マイグレーションファイルの編集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 作成されたファイルを VSCode で開いて、以下のように編集してください。
 *
 * 【編集前（作成直後の状態）】
 * public function up(): void
 * {
 *     Schema::table('expenses', function (Blueprint $table) {
 *         //
 *     });
 * }
 *
 * 【編集後】
 */

// 編集後のマイグレーションファイルの内容（コピーして貼り付けてください）：
/*
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('spent_at');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
*/

/**
 * 【コードの解説】
 *
 * $table->string('image_path') = 文字列を保存するカラムを追加する
 *   → 画像の「パス（保存場所の住所）」を文字列として保存します
 *   → 例：'expenses/xK3mN8pQrT.jpg' という文字列が入ります
 *   → 画像そのものではなく「住所」だけを保存するのがポイントです
 *
 * ->nullable()（ナラブル） = 「空っぽ（null）でも保存できる」という設定
 *   → 画像なしで支出を登録する場合もあるので、必須にしない
 *   → nullable をつけないと「画像は必ず添付しなければいけない」になってしまう
 *
 * ->after('spent_at')（アフター） = 「spent_at カラムの後ろに追加する」という位置指定
 *   → これがないと一番最後に追加される（どちらでも動作は同じです）
 *
 * down()（ダウン） = ロールバック（元に戻す）ときの処理
 *   → $table->dropColumn('image_path') = image_path カラムを削除する
 *   → 「やっぱり追加しなくていい」となったとき、元に戻せるようにする
 */

/**
 * 【マイグレーション実行】
 * 編集が完了したら、以下のコマンドで実行してください：
 */

// docker compose exec php php artisan migrate

/**
 * → 成功すると以下のように表示されます：
 *   INFO  Running migrations.
 *   xxxx_xx_xx_add_image_path_to_expenses_table .......... Xms DONE
 *
 * 実行後、phpMyAdmin で expenses テーブルに image_path カラムが
 * 追加されているか確認してください。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. Expense モデルの修正】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * app/Models/Expense.php の $fillable に 'image_path' を追加します。
 *
 * 【$fillable とは？（おさらい）】
 * 「まとめて保存（create/update）を許可するカラムのリスト」です。
 * リストに入っていないカラムは、まとめて保存ができません。
 *
 * 【修正後の $fillable】
 */

// app/Models/Expense.php の $fillable を以下のように修正してください：
/*
protected $fillable = [
    'user_id',
    'category',
    'amount',
    'description',
    'spent_at',
    'image_path',
];
*/

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. フォームの修正（create.blade.php）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 支出登録フォームにファイル選択欄を追加します。
 *
 * 【ファイルを送るために必要な特別な設定】
 *
 * 通常のフォームはテキストだけを送ります。
 * ファイル（画像）を送るには、フォームに特別な属性が必要です。
 *
 * 変更前：
 * <form method="POST" action="{{ route('expenses.store') }}">
 *
 * 変更後：
 * <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
 *
 * 【enctype="multipart/form-data" とは？】
 * enctype（エンクタイプ） = 「フォームのデータをどうやって送るか」の設定
 * multipart/form-data（マルチパート・フォームデータ） = 「テキストとファイルを混ぜて送る形式」
 *
 * 【たとえ話】
 * 普通の郵便（テキストのみ） → 手紙を封筒に入れて送る
 * multipart（ファイルあり） → 手紙と荷物を一緒に宅配便で送る
 * ファイルが「荷物」にあたるので、「宅配便」形式の指定が必要です。
 *
 * この enctype を忘れると、ファイルがサーバーに届きません。
 * よくあるミスなので、必ず確認してください。
 *
 * 【注意】
 * enctype はHTMLの仕様であり、Laravelに限らずWebのファイルアップロード全般で必要です。
 * スクールの教材では触れていないケースが多いですが、実務では必ず使います。
 *
 * 【追加する入力欄】
 * </form> の閉じタグの前（登録ボタンの前）に追加してください：
 */

// create.blade.php に追加するコード：
/*
<div>
    <label for="image">画像（任意）</label>
    <input type="file" id="image" name="image" accept="image/*">
</div>
*/

/**
 * 【コードの解説】
 *
 * type="file"（タイプ・ファイル） = ファイル選択ボタンを表示する
 * name="image"（ネーム・イメージ） = コントローラーで $request->file('image') として受け取る
 * accept="image/*"（アクセプト・イメージ） = 「画像ファイルのみ選択できるようにする」フィルタ
 *   image/* の * はワイルドカード（なんでもOK）= jpg・png・gif などすべての画像形式
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. コントローラーの修正（store メソッド）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * ExpenseController.php の store() に画像保存の処理を追加します。
 *
 * 【このプロジェクトの store() の構造（Day6 で実装済み）】
 * StoreUserRequest（ストア・ユーザー・リクエスト）というフォームリクエストを
 * 使ってバリデーションを行っています。
 *
 * フォームリクエストとは？
 * → バリデーションのルールを別ファイルに切り出したものです
 * → app/Http/Requests/StoreUserRequest.php に定義されています
 * → コントローラーがすっきり書ける利点があります
 *
 * 【修正後の store() メソッド】
 */

// ExpenseController.php の store() を以下のように修正してください：
/*
public function store(StoreUserRequest $request)
{
    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('expenses', 'public');
    }

    $expense = Expense::create(
        array_merge($request->validated(), [
            'user_id'    => auth()->id(),
            'image_path' => $imagePath,
        ])
    );

    $expense->tags()->sync($request->input('tag_ids', []));

    return redirect()->route('expenses.index')->with('success', '支出を登録しました');
}
*/

/**
 * 【コードの解説】
 *
 * $imagePath = null;
 * → 最初は「画像なし（null）」として準備しておく
 * → 画像が送られてきた場合だけ、値が入る
 *
 * $request->hasFile('image')（ハズファイル）
 * → 「image という名前でファイルが送られてきたか？」を確認する
 * → true（きた）/ false（こなかった）を返す
 * → フォームで name="image" とつけたので、ここでも 'image' と書く
 *
 * $request->file('image')（ファイル）
 * → 送られてきたファイルを取り出す
 *
 * ->store('expenses', 'public')（ストア）
 * → ファイルを storage/app/public/expenses/ フォルダに保存する
 * → Laravelが自動でランダムなファイル名をつけてくれる
 * → 保存後、'expenses/ランダム文字列.jpg' というパスを返す
 * → このパスを $imagePath に入れておく
 *
 * array_merge($request->validated(), [...])（アレイ・マージ）
 * → StoreUserRequest でバリデーション済みのデータに、
 *   user_id と image_path を追加してまとめて保存する
 *
 * 'image_path' => $imagePath
 * → 画像があれば 'expenses/abc123.jpg' が入る
 * → 画像がなければ null が入る
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【6. 一覧ページの修正（index.blade.php）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 保存した画像を一覧ページで表示します。
 *
 * 【Storage::url() とは？】
 * Storage::url()（ストレージ・ユーアールエル） = ファイルパスをURLに変換するメソッド
 *
 * データベースには 'expenses/abc123.jpg' というパスが保存されています。
 * これをブラウザで表示するには URL に変換する必要があります。
 *
 * 例：
 * $expense->image_path = 'expenses/abc123.jpg'
 * Storage::url($expense->image_path) = '/storage/expenses/abc123.jpg'
 *
 * 【たとえ話】
 * データベースには「東京都渋谷区○○1-2-3」という住所が入っている。
 * 地図アプリで表示するには、住所をGPS座標（緯度・経度）に変換する必要がある。
 * Storage::url() は「住所 → GPS座標」の変換ツールにあたります。
 *
 * 【Storage::url() を Blade で使う場合の注意】
 * Storage:: はBladeファイルで @php use... を書かなくても使えます。
 * Laravelが最初から使える状態にしてくれているためです。
 * スクールの教材で @php use... を書かなかったのもこの理由です。
 *
 * 【index.blade.php への追加箇所】
 * ①テーブルのヘッダー（<tr>）に <th>画像</th> を追加する
 * ② @foreach ループ内のタグ表示 </td> の直後に以下を追加する：
 */

// index.blade.php に追加するコード：
/*
<td>
    @if ($expense->image_path)
        <img src="{{ Storage::url($expense->image_path) }}" alt="支出画像" width="100">
    @endif
</td>
*/

/**
 * 【コードの解説】
 *
 * @if ($expense->image_path)
 * → image_path が null（画像なし）の場合は何も表示しない
 * → null でない（画像あり）の場合だけ <img> タグを表示する
 *
 * Storage::url($expense->image_path)
 * → データベースのパスをブラウザ用URLに変換する
 * → APP_URL=http://localhost:8080 が設定されていることで正しいURLが生成される
 *
 * width="100"
 * → 画像の横幅を100ピクセルで表示する（大きすぎず小さすぎず）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📋 【実装の順番まとめ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【0】.env の APP_URL を確認・修正する
 *   APP_URL=http://localhost:8080
 *   変更後は以下のコマンドでキャッシュをクリアする：
 *   docker compose exec php php artisan config:clear
 *
 * 【1】マイグレーションファイルを作成する
 *   docker compose exec php php artisan make:migration add_image_path_to_expenses_table --table=expenses
 *
 * 【2】作成されたマイグレーションファイルを編集する
 *   up()   → $table->string('image_path')->nullable()->after('spent_at');
 *   down() → $table->dropColumn('image_path');
 *
 * 【3】マイグレーションを実行する
 *   docker compose exec php php artisan migrate
 *
 * 【4】Expense モデルの $fillable に 'image_path' を追加する
 *   app/Models/Expense.php
 *
 * 【5】create.blade.php を修正する
 *   ・<form> タグに enctype="multipart/form-data" を追加
 *   ・ファイル選択欄（<input type="file">）を追加
 *
 * 【6】ExpenseController.php の store() を修正する
 *   ・画像保存の処理（hasFile / store）を追加
 *   ・array_merge で image_path を一緒に保存
 *
 * 【7】index.blade.php を修正する
 *   ・<th>画像</th> をヘッダーに追加
 *   ・<td> に画像表示コードを追加
 *
 * 【8】動作確認
 *   ・ブラウザで支出を登録（画像あり）
 *   ・一覧ページで画像が表示されるか確認
 *   ・phpMyAdmin で image_path に値が入っているか確認
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ❓ 【よくある質問 Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. enctype を忘れるとどうなりますか？
 * A1. ファイルがサーバーに届きません。
 *     $request->hasFile('image') が常に false になり、
 *     画像を選択しても保存されません。
 *     ファイルアップロードで最もよくあるミスです。
 *
 * Q2. store() の第2引数 'public' は何ですか？
 * A2. 「どのディスク（保存場所の種類）を使うか」の指定です。
 *     'public' = storage/app/public/ に保存する
 *     Step8-01で .env に FILESYSTEM_DISK=public を設定しましたが、
 *     明示的に指定することで確実に正しい場所に保存されます。
 *
 * Q3. ファイル名はどうなりますか？
 * A3. Laravelが自動的にランダムな文字列のファイル名をつけます。
 *     例：expenses/xK3mN8pQrT2wYvBj.jpg
 *     これにより同じ名前のファイルが上書きされる問題を防ぎます。
 *
 * Q4. image_path に保存されるのはURLですか？
 * A4. URLではなく「相対パス（そうたいぱす）」です。
 *     例：'expenses/abc123.jpg'
 *     これを Storage::url() に渡すと '/storage/expenses/abc123.jpg' というURLになります。
 *     パスで保存しておくことで、ドメインが変わってもURLを作り直せます。
 *
 * Q5. 画像なしで登録したらどうなりますか？
 * A5. $imagePath が null のまま保存されます。
 *     index.blade.php の @if($expense->image_path) で
 *     null の場合は画像を表示しないようにしているので問題ありません。
 *
 * Q6. APP_URL にポート番号がないと何が起きますか？
 * A6. Storage::url() が生成するURLが http://localhost/storage/... になり、
 *     実際のアプリのURL http://localhost:8080/storage/... とズレてしまいます。
 *     画像ファイルは存在するのに画像が表示されない、という症状になります。
 *     .env の APP_URL=http://localhost:8080 を確認してください。
 *
 * Q7. StoreUserRequest とは何ですか？
 * A7. バリデーションのルールを別ファイルに切り出したクラスです。
 *     app/Http/Requests/StoreUserRequest.php に定義されています。
 *     コントローラーで Request の代わりに使うことで、
 *     コントローラーがすっきり書けるようになります。
 *     Day6 で学んだ FormRequest（フォームリクエスト）の応用です。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📚 【用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * enctype（エンクタイプ）
 * └─ フォームのデータ送信形式を指定する属性
 * └─ ファイルを送る場合は必ず multipart/form-data を指定する
 *
 * multipart/form-data（マルチパート・フォームデータ）
 * └─ テキストとファイルを一緒に送るための形式
 *
 * hasFile()（ハズファイル）
 * └─ 「このファイルが送られてきたか？」を確認するメソッド
 * └─ true（送られてきた）/ false（送られていない）を返す
 *
 * store()（ストア）
 * └─ アップロードされたファイルをstorageに保存するメソッド
 * └─ 保存先のパスを文字列で返す
 *
 * Storage::url()（ストレージ・ユーアールエル）
 * └─ ファイルパスをブラウザでアクセスできるURLに変換するメソッド
 * └─ APP_URL の設定に基づいてURLを生成する
 *
 * nullable（ナラブル）
 * └─ 「nullでもOK」= 空っぽでも保存できるカラムの設定
 *
 * $fillable（フィラブル）
 * └─ まとめて保存（create/update）を許可するカラムのリスト
 *
 * 相対パス（そうたいぱす）
 * └─ URLではなく「ファイルの場所を表す文字列」
 * └─ 例：'expenses/abc123.jpg'
 *
 * StoreUserRequest（ストア・ユーザー・リクエスト）
 * └─ バリデーションのルールを別ファイルに切り出したクラス
 * └─ app/Http/Requests/StoreUserRequest.php に定義されている
 * └─ FormRequest（フォームリクエスト）を継承している
 *
 * array_merge()（アレイ・マージ）
 * └─ 2つの配列を1つにまとめるPHPの関数
 * └─ validated() の結果に user_id と image_path を追加するために使用
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 💬 【第2部：学習中の質問と回答】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 */

/**
 * Q. StoreUserRequest が既にあるのに、教材では Request を使う形で書かれていた。
 *    どちらが正しいですか？
 *
 * A. このプロジェクトでは StoreUserRequest を使うのが正しいです。
 *    教材は事前に既存ファイルを確認せずに作成してしまったため、
 *    Request を直接使う汎用的な形で書いてしまいました。
 *    既存の構造を活かして StoreUserRequest を使う形に修正しました。
 *
 *    【Request vs StoreUserRequest の違い】
 *    Request → バリデーションをコントローラー内に直接書く
 *    StoreUserRequest → バリデーションを別ファイルに切り出している
 *    どちらも動きますが、このプロジェクトでは後者で統一されています。
 */

/**
 * Q. enctype="multipart/form-data" はスクールの教材では学びませんでした。
 *    なぜですか？
 *
 * A. enctype="multipart/form-data" はHTMLの仕様であり、
 *    「Laravel固有の知識」ではなく「Web全般の知識」に分類されます。
 *    スクールのLaravel教材では扱わないケースが多いです。
 *    ただし実務では「ファイルアップロードを実装するたびに必ず必要」な知識です。
 *
 *    【たとえ話】
 *    普通の郵便（テキストのみ） → 手紙を封筒に入れて送る
 *    multipart（ファイルあり） → 手紙と荷物を一緒に宅配便で送る
 *    ファイルが「荷物」にあたるので、「宅配便」形式の指定が必要です。
 */

/**
 * Q. Blade で Storage:: を使うときに @php use... を書く必要はありますか？
 *
 * A. 不要です。
 *    Storage ファサードはLaravelが最初から使える状態にしてくれているため、
 *    @php use Illuminate\Support\Facades\Storage; @endphp は書かなくても動きます。
 *    スクールの教材でも書いていなかったのはこの理由です。
 */

/**
 * Q. 画像ファイルは storage に保存されているのに画像が表示されなかった原因は？
 *
 * A. .env の APP_URL にポート番号が含まれていなかったためです。
 *
 *    Storage::url() は APP_URL を基にURLを生成します。
 *    APP_URL=http://localhost の場合：
 *    → http://localhost/storage/expenses/abc123.jpg（ポートなし・間違い）
 *
 *    APP_URL=http://localhost:8080 の場合：
 *    → http://localhost:8080/storage/expenses/abc123.jpg（正しい）
 *
 *    .env を修正後、以下のコマンドでキャッシュをクリアしてください：
 *    docker compose exec php php artisan config:clear
 */
