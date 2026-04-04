<?php

/**
 * 📘 Day7 教材（Step7-01：認証と認可の違い・Gateの基礎）
 *
 * この教材では「ログインしているかどうか」と「何をしていいか」の違いと、
 * Laravelの「Gate（ゲート）」という仕組みを学びます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【認証（にんしょう）と認可（にんか）の違い】
 *
 * 「認証」と「認可」、この2つの言葉はよく混同されます。
 * でも意味は全然違います。
 *
 * - 認証（Authentication：オーセンティケーション） = 「あなたは誰ですか？」
 * - 認可（Authorization：オーソリゼーション）    = 「あなたは何をしていいですか？」
 *
 * 【たとえ話：映画館で考える】
 *
 * 映画館のスタッフをイメージしてください。
 *
 * 認証 = チケットを確認するスタッフ
 *   「チケットをお持ちですか？（＝ログインしていますか？）」
 *   チケットがあれば映画館に入れます。
 *   チケットがなければ入れません。
 *
 * 認可 = 座席を案内するスタッフ
 *   「お客様はS席のチケットをお持ちですね。
 *    ではS席エリアにお入りください。
 *    A席エリアには入れません。」
 *   チケット（ログイン）があっても、S席を持っていない人はS席エリアに入れません。
 *
 * 【まとめ】
 * - Day4（認証）= 「映画館に入れるか（ログインしているか）」を確認
 * - Day7（認可）= 「どのエリアに入れるか（何をしていいか）」を確認
 *
 * ---
 *
 * 【今日学ぶ3つのこと】
 * 1. 認証と認可の概念の違い
 * 2. Gate（ゲート）の基礎と書き方
 * 3. Bladeテンプレートでの使い方
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. 認証（Authentication）と認可（Authorization）の整理】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【認証（Authentication）】
 * 読み方：オーセンティケーション
 * 略称  ：authn（オートエヌ）とも書く
 *
 * 意味：「この人は本物のユーザーか？」を確認すること
 * 実装：ログイン・ログアウト・会員登録
 * Day4で：Laravel Fortifyを使って実装済み
 *
 * 確認方法（代表的なもの）：
 *   auth()->check()  = ログインしているか（trueまたはfalse）
 *   auth()->user()   = ログイン中のユーザー情報を取得
 *   Auth::user()     = 同じ意味（書き方が違うだけ）
 *
 * 【認可（Authorization）】
 * 読み方：オーソリゼーション
 * 略称  ：authz（オートゼット）とも書く
 *
 * 意味：「この人は○○していい権限があるか？」を確認すること
 * 実装：Gate（ゲート）・Policy（ポリシー）
 * Day7で：これから学ぶ
 *
 * 例：
 * - ログインしているユーザーAさんが、Bさんの投稿を削除しようとした
 * - 認証 = Aさんはログインしている → ✅ OK
 * - 認可 = AさんはBさんの投稿を削除する権限がある？ → ❌ NG
 *
 * 【なぜ認可が必要か？】
 *
 * 認証だけでは不十分な理由を考えてみましょう。
 *
 * 例えば、ブログサービスで考えます：
 * - ユーザーAさん：自分の記事を「編集」「削除」できる
 * - ユーザーBさん：Aさんの記事を見ることはできるが、「編集」「削除」はできない
 * - 管理者：全ての記事を「編集」「削除」できる
 *
 * これを「誰がログインしているか」だけで判断するのは無理です。
 * 「ログインしているAさんは、この記事に対して何をしていいか」という判断が必要です。
 * これが認可です。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. Gate（ゲート）とは？】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Gate（ゲート） = 「～していい？」を判定するシンプルな仕組み
 *
 * 【たとえ話：改札口】
 * 駅の改札口（ゲート）をイメージしてください。
 * ICカードをタッチすると「通っていい」か「通れない」か判定されます。
 *
 * LaravelのGateも同じです。
 * 「この人は○○していい？」と聞くと、「OK（true）」か「NG（false）」を返します。
 *
 * 【Gateの特徴】
 * - 書く場所：app/Providers/AppServiceProvider.php（アップ サービスプロバイダー）
 * - シンプルな判定に向いている
 * - モデルと直接紐づかない（紐づける場合はStep7-02のPolicyを使う）
 *
 * 【AppServiceProvider.phpとは？】
 * Laravelが起動するときに最初に読み込まれるファイルです。
 * アプリ全体の「準備・設定」を書く場所です。
 *
 * 【書き方の基本】
 * Gate::define('ゲートの名前', function($user, ...) {
 *     // trueを返すと「OK」、falseを返すと「NG」
 *     return 条件;
 * });
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. Gateを書いてみよう】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【例：管理者だけが「記事を削除できる」Gateを作る】
 *
 * app/Providers/AppServiceProvider.php に以下を追加します。
 *
 * 【AppServiceProvider.phpの場所】
 * laravel-practice/src/laravel/app/Providers/AppServiceProvider.php
 *
 * 【変更前（最初の状態）】
 *
 * namespace App\Providers;
 * use Illuminate\Support\ServiceProvider;
 *
 * class AppServiceProvider extends ServiceProvider
 * {
 *     public function boot(): void
 *     {
 *         // ここに書く
 *     }
 * }
 *
 * 【変更後（Gateを追加）】
 *
 * namespace App\Providers;
 * use Illuminate\Support\Facades\Gate;
 * use Illuminate\Support\ServiceProvider;
 *
 * class AppServiceProvider extends ServiceProvider
 * {
 *     public function boot(): void
 *     {
 *         // Gate::define（ゲート デファイン）= 「○○という名前のGateを定義する」
 *         // 'delete-post' = このGateの名前（自由につけられる）
 *         // $user        = 現在ログインしているユーザー
 *         // $post        = 判定対象の投稿
 *         Gate::define('delete-post', function ($user, $post) {
 *             // ユーザーのIDと投稿の作成者IDが一致するときだけ「OK」
 *             return $user->id === $post->user_id;
 *         });
 *
 *         // 管理者かどうかで判定するGateの例
 *         Gate::define('admin-only', function ($user) {
 *             return $user->is_admin === true;
 *         });
 *     }
 * }
 *
 * 【コードの意味を分解】
 *
 * Gate::define('delete-post', function ($user, $post) {
 *     return $user->id === $post->user_id;
 * });
 *
 * - Gate::define = 「このGateを作る」という命令
 * - 'delete-post' = Gateの名前（呼び出すときに使う）
 * - function ($user, $post) = このGateが受け取る情報
 *   - $user = ログイン中のユーザー（Laravelが自動的に渡してくれる）
 *   - $post = 比べたい投稿（呼び出すときに渡す）
 * - return $user->id === $post->user_id
 *   = 「ログイン中のユーザーのIDと投稿の作成者IDが同じならtrue（OK）」
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. Gateを使ってみよう（コントローラーでの使い方）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Gateを定義したら、コントローラーやBladeから呼び出します。
 *
 * 【コントローラーでの書き方：3つの方法】
 *
 * ①【Gate::allows（ゲート オールス）】= 「○○していい？」（trueかfalseで返す）
 *
 * use Illuminate\Support\Facades\Gate;
 *
 * public function destroy($id)
 * {
 *     $post = Post::find($id);
 *
 *     if (Gate::allows('delete-post', $post)) {
 *         // OK → 削除する
 *         $post->delete();
 *         return redirect('/posts')->with('success', '削除しました');
 *     } else {
 *         // NG → エラーを返す
 *         abort(403);  // 403 = 「アクセス禁止」というHTTPステータスコード
 *     }
 * }
 *
 * ②【Gate::denies（ゲート デナイズ）】= 「○○してはいけない？」（allowsの逆）
 *
 * if (Gate::denies('delete-post', $post)) {
 *     abort(403);
 * }
 * $post->delete();
 *
 * ③【$this->authorize（ジス オーソライズ）】= 「許可なければ自動的に403エラー」
 *
 * public function destroy($id)
 * {
 *     $post = Post::find($id);
 *     $this->authorize('delete-post', $post);  // ← NGなら自動でabort(403)
 *     $post->delete();
 *     return redirect('/posts')->with('success', '削除しました');
 * }
 *
 * 【3つの違いのまとめ】
 *
 * - Gate::allows()  = 「OK？」→ 自分でif文を書く必要がある
 * - Gate::denies()  = 「NG？」→ allowsの逆で、自分でif文を書く
 * - $this->authorize() = 「OKでなければ自動でエラー」→ 一番シンプル
 *
 * 【どれを使えばいい？】
 * 実務では $this->authorize() が最もよく使われます。
 * コードがシンプルになるからです。
 *
 * 【abort(403)とは？】
 * abort（アボート）= 「中止する」
 * 403（フォースリースリー）= HTTPステータスコードで「アクセス禁止」の意味
 * abort(403)と書くと、Laravelが自動的に「アクセス禁止」のエラーページを表示します。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. Bladeテンプレートでの使い方】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 「削除ボタン」を、削除権限がある人にだけ表示したい場合、
 * Bladeテンプレートにも書けます。
 *
 * 【@can（アットキャン）と@cannot（アットキャノット）】
 *
 * @can('delete-post', $post)
 *     {{-- このユーザーはdelete-postのGateが通る（OKの場合）に表示 --}}
 *     <form method="POST" action="/posts/{{ $post->id }}">
 *         @csrf
 *         @method('DELETE')
 *         <button type="submit">削除する</button>
 *     </form>
 * @endcan
 *
 * @cannot('delete-post', $post)
 *     {{-- このユーザーはdelete-postのGateが通らない（NGの場合）に表示 --}}
 *     <p>この投稿を削除する権限がありません</p>
 * @endcannot
 *
 * 【意味の分解】
 *
 * @can('delete-post', $post)
 *   = 「現在ログインしているユーザーが、$postに対してdelete-postを実行できるなら」
 *
 * @cannot('delete-post', $post)
 *   = 「できないなら」（@canの逆）
 *
 * 【注意点】
 * @can/@cannotはあくまで「表示・非表示」の制御です。
 * コントローラーでも必ずGateチェックをしてください。
 *
 * なぜなら、ボタンが表示されていなくても、
 * URLに直接アクセスすれば削除処理が実行できてしまうからです。
 * フロント（Blade）とバック（コントローラー）の両方で守ることが大切です。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【6. 実際に手を動かしてみよう】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 今日は「usersテーブル」のis_admin（イズアドミン）カラムを使って、
 * 「管理者だけができる操作」を Gate で判定するシンプルな例を作ります。
 *
 * 【手順1：AppServiceProvider.php にGateを追加する】
 *
 * ファイルを開いてください：
 * laravel-practice/src/laravel/app/Providers/AppServiceProvider.php
 *
 * 【現在のファイルの内容を確認】
 * まず今のファイルの内容をVSCodeで確認してください。
 * （Laravelの初期状態ではbootメソッドが空になっています）
 */

// 【手順1：AppServiceProvider.php を以下のように書き換えてください】
// ファイルパス：app/Providers/AppServiceProvider.php

// 以下のコードをコピーしてAppServiceProvider.phpに貼り付けてください：
/*
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 管理者かどうかを判定するGate
        // 'admin-only' = このGateの名前
        // $user->is_admin = usersテーブルのis_adminカラム（1なら管理者）
        Gate::define('admin-only', function ($user) {
            return $user->is_admin === 1;
        });

        // 投稿の作成者かどうかを判定するGate
        // $user = ログイン中のユーザー
        // $post = 判定したい投稿
        Gate::define('update-post', function ($user, $post) {
            return $user->id === $post->user_id;
        });
    }
}
*/

/**
 * 【手順2：マイグレーションでis_adminカラムを追加する】
 *
 * 現在のusersテーブルにis_adminカラムはありません。
 * マイグレーションを作成して追加します。
 */

// 以下のコマンドをターミナルで実行してください（Dockerコンテナ内）：
// cd ~/venpro/laravel-practice
// docker compose exec app php artisan make:migration add_is_admin_to_users_table --table=users

/**
 * 作成されたマイグレーションファイルを編集します。
 * ファイルの場所：database/migrations/xxxx_xx_xx_xxxxxx_add_is_admin_to_users_table.php
 */

// 以下のコードをコピーしてマイグレーションファイルに貼り付けてください：
/*
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // is_admin（イズアドミン）カラムを追加
            // boolean（ブーリアン）= true（1）かfalse（0）の2択
            // default(0)（デフォルト）= 初期値は0（管理者ではない）
            $table->boolean('is_admin')->default(0)->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
*/

/**
 * 【手順3：マイグレーションを実行する】
 */

// ターミナルで以下のコマンドを実行してください：
// docker compose exec app php artisan migrate

/**
 * 【手順4：tinkerでテストデータを準備する】
 */

// ターミナルで以下のコマンドを実行してください：
// docker compose exec app php artisan tinker

// tinker内で以下を実行してください（1行ずつ）：

// 1. ID=1のユーザーを管理者にする（以下のコードをコピーして実行してください）：
// $user = User::find(1); $user->is_admin = 1; $user->save();

// 2. 管理者フラグを確認する（以下のコードをコピーして実行してください）：
// User::find(1)->is_admin;

/**
 * → 1 と表示されれば管理者フラグが設定されています
 *
 * 【手順5：tinkerでGateを確認する】
 */

// tinker内で以下を実行してください：

// 3. ユーザーとしてGateをテストする（以下のコードをコピーして実行してください）：
// $user = User::find(1); Gate::forUser($user)->allows('admin-only');

/**
 * → true と表示されれば管理者ユーザーに対してGateが正常に機能しています
 */

// 4. 別のユーザーでテストする（以下のコードをコピーして実行してください）：
// $user2 = User::find(2); Gate::forUser($user2)->allows('admin-only');

/**
 * → false と表示されれば、管理者でないユーザーはGateをパスできないことを確認できます
 *
 * ※ User::find(2) に該当するユーザーがいない場合はエラーが出ます。
 *   その場合は別のユーザーIDを試してください。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【7. よくある疑問Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. Gate と middleware（ミドルウェア）の違いは何ですか？
 * A1. どちらもアクセス制限に使いますが、守備範囲が違います。
 *
 *     middleware = ルートレベルの制限（このURLにアクセスできるか）
 *     Gate       = 操作レベルの制限（この操作をしていいか）
 *
 *     例：
 *     middleware：「ログインしていない人はこのページに入れない」
 *     Gate：「ログインしていても、自分の投稿でないと削除できない」
 *
 *     映画館のたとえ：
 *     middleware = 映画館の入口（チケットなしは入れない）
 *     Gate       = 座席区分（S席の人しかS席には座れない）
 *
 * ---
 *
 * Q2. $userは自動的に渡されるのですか？
 * A2. はい、Gate::defineの第1引数の$userは、
 *     現在ログインしているユーザーがLaravelによって自動的に渡されます。
 *     自分でログイン中のユーザーを取得する必要はありません。
 *
 *     ただし、ログインしていない場合はnull（ヌル）が渡されます。
 *     その場合を考慮する場合は以下のように書きます：
 *
 *     Gate::define('admin-only', function (?User $user) {
 *         return $user?->is_admin === 1;
 *     });
 *     （?User = ユーザーがnullの可能性があることを示す）
 *
 * ---
 *
 * Q3. Gate の名前は何でもいいですか？
 * A3. 自由に決められますが、わかりやすい名前をつけましょう。
 *
 *     慣習的に「動詞-対象」の形が多いです：
 *     - 'update-post'（投稿を更新する）
 *     - 'delete-comment'（コメントを削除する）
 *     - 'admin-only'（管理者のみ）
 *
 * ---
 *
 * Q4. abort(403)とはどういう意味ですか？
 * A4. abort（アボート）は「中断する」という意味で、
 *     引数の数字はHTTPステータスコード（エイチティーティーピー ステータスコード）です。
 *
 *     代表的なHTTPステータスコード：
 *     - 200 = OK（正常）
 *     - 404 = Not Found（ページが見つからない）
 *     - 403 = Forbidden（アクセス禁止）
 *     - 500 = Internal Server Error（サーバーエラー）
 *
 *     abort(403)と書くと、Laravelが「403.blade.php」を探して
 *     エラーページを表示します。
 *
 * ---
 *
 * Q5. GateとPolicyはどう使い分けますか？
 * A5. シンプルな判定ならGate、モデルに関係する判定ならPolicyが向いています。
 *
 *     Gate  → シンプルな条件（「管理者か」「ログインしているか」など）
 *     Policy → 特定のモデルに関する操作（「この投稿を編集・削除できるか」など）
 *
 *     Policyについては Step7-02 で詳しく学びます。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📚 【用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 認証（Authentication：オーセンティケーション）
 * └─ 「あなたは誰？」を確認する仕組み（ログイン・ログアウト）
 *
 * 認可（Authorization：オーソリゼーション）
 * └─ 「あなたは何をしていい？」を確認する仕組み
 *
 * Gate（ゲート）
 * └─ 「○○していい？」をシンプルに判定するLaravelの仕組み
 *
 * Gate::define（ゲート デファイン）
 * └─ 「この名前のGateをこのルールで作る」という命令
 *
 * Gate::allows（ゲート オールス）
 * └─ 「○○はOK？」→ trueかfalseで返す
 *
 * Gate::denies（ゲート デナイズ）
 * └─ 「○○はNG？」→ allowsの逆
 *
 * $this->authorize（ジス オーソライズ）
 * └─ 「OKでなければ自動でabort(403)を実行する」省略形
 *
 * abort(403)（アボート フォースリースリー）
 * └─ 「アクセス禁止」エラーページを表示して処理を中断する
 *
 * @can（アットキャン）
 * └─ BladeでGateがOKの場合だけ表示するディレクティブ
 *
 * @cannot（アットキャノット）
 * └─ BladeでGateがNGの場合だけ表示するディレクティブ
 *
 * is_admin（イズアドミン）
 * └─ 管理者かどうかを示すカラム名（1=管理者、0=一般ユーザー）
 *
 * boolean（ブーリアン）
 * └─ true（1）かfalse（0）の2値しか持てないデータ型
 *
 * AppServiceProvider（アップ サービスプロバイダー）
 * └─ Laravelが起動するときに読み込まれる準備ファイル
 *    Gateの定義はここに書く
 */
