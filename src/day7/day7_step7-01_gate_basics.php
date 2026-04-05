<?php

/**
 * 📘 Day7 教材（Step7-01：認証と認可の違い・Gateの基礎）統合版
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
 * 【今日学ぶ4つのこと】
 * 1. 認証と認可の概念の違い
 * 2. Gate（ゲート）の基礎と書き方（AppServiceProvider.php）
 * 3. Bladeテンプレートでの使い方（@can / @cannot）
 * 4. ブラウザで実際に確認する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. 認証（Authentication）と認可（Authorization）の整理】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【認証（Authentication：オーセンティケーション）】
 *
 * 意味：「この人は本物のユーザーか？」を確認すること
 * 実装：ログイン・ログアウト・会員登録
 * Day4で：Laravel Fortifyを使って実装済み
 *
 * 【認可（Authorization：オーソリゼーション）】
 *
 * 意味：「この人は○○していい権限があるか？」を確認すること
 * 実装：Gate（ゲート）・Policy（ポリシー）
 * Day7で：これから学ぶ
 *
 * 【なぜ認可が必要か？】
 *
 * 例えば、ブログサービスで考えます：
 * - ユーザーAさん：自分の記事を「編集」「削除」できる
 * - ユーザーBさん：Aさんの記事は見られるが、「編集」「削除」はできない
 * - 管理者：全ての記事を「編集」「削除」できる
 *
 * 「ログインしているか」だけで判断するのは無理です。
 * 「ログインしているAさんは、この記事に対して何をしていいか」という
 * 判断が必要です。これが認可です。
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
 * - 書く場所：app/Providers/AppServiceProvider.php
 * - シンプルな判定に向いている
 * - モデルと直接紐づかない（紐づける場合はStep7-02のPolicyを使う）
 *
 * 【AppServiceProvider.php（アップ サービスプロバイダー）とは？】
 * Laravelが起動するときに最初に読み込まれるファイルです。
 * アプリ全体の「準備・設定」を書く場所です。
 * Gateの定義はここに書きます。
 *
 * 【GateとMiddleware（ミドルウェア）の違い】
 *
 * どちらもアクセス制限に使いますが、守備範囲が違います。
 *
 * - Middleware（ミドルウェア）= ルートレベルの制限
 *   「このURLにログインしていない人はアクセスできない」
 *   → 映画館の入口（チケットなしは入れない）
 *
 * - Gate（ゲート）= 操作レベルの制限
 *   「ログインしていても、自分の投稿でないと削除できない」
 *   → 座席区分（S席の人しかS席には座れない）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. Gateの書き方（読んで理解するセクション）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * ※ このセクションは読んで理解するだけです。
 *   実際にファイルを編集するのは【4. 実際に手を動かしてみよう】です。
 *
 * 【基本的な書き方】
 *
 * Gate::define('ゲートの名前', function($user) {
 *     return 条件;  // trueを返すとOK、falseを返すとNG
 * });
 *
 * 【コードの意味を分解】
 *
 * Gate::define('admin-only', function ($user) {
 *     return $user->is_admin === 1;
 * });
 *
 * - Gate::define（ゲート デファイン） = 「このGateを作る」という命令
 * - 'admin-only' = Gateの名前（呼び出すときに使う）
 * - function ($user) = このGateが受け取る情報
 *   - $user = ログイン中のユーザー（Laravelが自動的に渡してくれる）
 * - return $user->is_admin === 1
 *   = 「is_adminが1（管理者）ならtrue（OK）、そうでなければfalse（NG）」
 *
 * 【$userは自動的に渡される】
 * Gate::defineの$userは、現在ログインしているユーザーが
 * Laravelによって自動的に渡されます。
 * 自分でログイン中のユーザーを取得する必要はありません。
 *
 * 【Gate名の命名ルール】
 * 慣習的に「動詞-対象」の形が多いです：
 * - 'admin-only'（管理者のみ）
 * - 'update-post'（投稿を更新する）
 * - 'delete-comment'（コメントを削除する）
 *
 * ---
 *
 * 【コントローラーでの使い方（参考）】
 *
 * コントローラーでGateを使う場合は以下の3つの書き方があります。
 *
 * ①【Gate::allows()（ゲート オールス）】= 「○○していい？」（trueかfalseで返す）
 *
 * if (Gate::allows('admin-only')) {
 *     // OKの処理
 * } else {
 *     abort(403);  // NGなら「アクセス禁止」エラー
 * }
 *
 * ②【Gate::denies()（ゲート デナイズ）】= 「○○してはいけない？」（allowsの逆）
 *
 * if (Gate::denies('admin-only')) {
 *     abort(403);
 * }
 *
 * ③【$this->authorize()（ジス オーソライズ）】= 「許可なければ自動的に403エラー」
 * （一番シンプルで実務でよく使う）
 *
 * $this->authorize('admin-only');
 *
 * 【3つの違いのまとめ】
 * - Gate::allows()     → 「OK？」を自分でif文を書いて判定する
 * - Gate::denies()     → 「NG？」を自分でif文を書いて判定する（allowsの逆）
 * - $this->authorize() → NGなら自動でエラー。コードが一番シンプル
 *
 * 【abort(403)（アボート フォー・オー・スリー）とは？】
 * abort（アボート）= 「中断する」
 * 403（フォー・オー・スリー）= HTTPステータスコード（エイチティーティーピー ステータスコード）で
 *                              「アクセス禁止（Forbidden：フォービドゥン）」の意味
 * abort(403)と書くと、Laravelが自動的に「アクセス禁止」のエラーページを表示します。
 *
 * 【代表的なHTTPステータスコード】
 * - 200 = OK（正常）
 * - 403 = Forbidden（フォービドゥン）= アクセス禁止
 * - 404 = Not Found（ノット ファウンド）= ページが見つからない
 * - 500 = Internal Server Error（インターナル サーバー エラー）= サーバーエラー
 *
 * ---
 *
 * 【Bladeでの使い方（参考）】
 *
 * @can（アットキャン）= GateがOKの場合だけ表示する
 * @cannot（アットキャノット）= GateがNGの場合だけ表示する
 * @auth（アットオース）= ログインしている場合だけ処理する
 *
 * @can('admin-only')
 *     管理者メニュー（管理者だけに表示）
 * @endcan
 *
 * @auth
 *     @cannot('admin-only')
 *         一般ユーザーメニュー（ログインしていて、かつ管理者でない人だけに表示）
 *     @endcannot
 * @endauth
 *
 * 【@authと@cannotを組み合わせる理由】
 * @cannotだけだと、ログインしていない場合にも表示されてしまいます。
 * ログインしていない場合、GateはNGとして扱うため@cannotの条件に当てはまるからです。
 * @auth（ログインしている場合）の中に@cannotを書くことで、
 * 「ログインしていて、かつ管理者でない人」だけに表示できます。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. 実際に手を動かしてみよう】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 今日は以下の4つの作業をします。
 * ① AppServiceProvider.phpにGateを追加する
 * ② usersテーブルにis_adminカラムを追加する（マイグレーション）
 * ③ tinkerでGateの動きを確認する
 * ④ home.blade.phpでブラウザ上の表示・非表示を確認する
 */

/**
 * 【手順1：AppServiceProvider.phpを書き換える】
 *
 * ファイルの場所：
 * laravel-practice/src/laravel/app/Providers/AppServiceProvider.php
 */

// 以下のコードをコピーしてAppServiceProvider.phpに貼り付けてください：
/*
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
 * usersテーブルに「管理者かどうか」を記録するカラムを追加します。
 */

// マイグレーションファイルを作成（以下のコマンドをターミナルで実行してください）：
// docker compose exec php php artisan make:migration add_is_admin_to_users_table --table=users

/**
 * 作成されたファイル（database/migrations/xxxx_add_is_admin_to_users_table.php）を
 * 以下のように編集してください：
 */

// 以下のコードをup()とdown()に貼り付けてください：
/*
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // is_admin（イズアドミン）カラムを追加
        // boolean（ブーリアン）= 1か0の2択（1=管理者、0=一般ユーザー）
        // default(0)（デフォルト）= 初期値は0（管理者ではない）
        // after('email')（アフター）= emailカラムの直後に追加
        $table->boolean('is_admin')->default(0)->after('email');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // マイグレーションを元に戻すときにis_adminカラムを削除する
        $table->dropColumn('is_admin');
    });
}
*/

// マイグレーションを実行してください（以下のコマンドをターミナルで実行してください）：
// docker compose exec php php artisan migrate

/**
 * → phpMyAdminでusersテーブルを確認すると、
 *   emailの直後にis_adminカラムが追加されています。
 */

/**
 * 【手順3：tinkerでGateの動きを確認する】
 *
 * ※ この手順はDocker環境で学習している場合の手順です。
 *
 * 通常の tinker 起動コマンド（php artisan tinker）だと
 * 設定ファイルへの書き込み権限エラーが出る場合があります。
 * 以下の特殊なコマンドで起動してください：
 */

// docker compose exec -e XDG_CONFIG_HOME=/tmp php php artisan tinker

/**
 * 【-e XDG_CONFIG_HOME=/tmp の意味】
 * tinkerは設定ファイルを「/.config/psysh」に保存しようとしますが、
 * Dockerコンテナ内ではこのフォルダへの書き込み権限がなくエラーになります。
 * -e XDG_CONFIG_HOME=/tmp を付けることで、
 * 設定の保存場所を書き込みOKな/tmpフォルダに変更できます。
 *
 * tinker起動後、以下を1行ずつ実行してください：
 */

// 1. ID=1のユーザーを管理者にする（以下のコードをコピーして実行してください）：
// $user = User::find(1); $user->is_admin = 1; $user->save();

/**
 * → true と表示されれば保存成功です
 * → phpMyAdminでusersテーブルを確認すると is_admin が 0 → 1 に変わっています
 */

// 2. 管理者GateがOKになるか確認する（以下のコードをコピーして実行してください）：
// Gate::forUser($user)->allows('admin-only');

/**
 * → true と表示されれば管理者ユーザーに対してGateが正常に機能しています
 */

// 3. 別のユーザーでNGになるか確認する（以下のコードをコピーして実行してください）：
// $user2 = User::find(2); Gate::forUser($user2)->allows('admin-only');

/**
 * → false と表示されれば、管理者でないユーザーはGateをパスできません
 * ※ ID=2のユーザーがいない場合はエラーが出ます。その場合は手順4に進んでください。
 */

// 4. tinkerを終了する（以下のコードをコピーして実行してください）：
// exit

/**
 * 【手順4：home.blade.phpでブラウザ上の表示・非表示を確認する】
 *
 * ファイルの場所：
 * laravel-practice/src/laravel/resources/views/home.blade.php
 */

// 以下のコードをコピーしてhome.blade.phpに貼り付けてください：
/*
@extends('layouts.app')

@section('title', 'ホームページ')

@section('content')
    <p>こんにちは、KOH！LaravelのBladeテンプレートが正しく動作しています！</p>

    {{-- @can（アットキャン）= admin-onlyゲートがOKの場合だけ表示する --}}
    @can('admin-only')
        <div style="margin-top: 16px; padding: 12px; background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px;">
            ⭐ 管理者メニュー（管理者だけに見えています）
        </div>
    @endcan

    {{-- @auth = ログインしている場合だけ処理する --}}
    {{-- @cannot = admin-onlyゲートがNGの場合だけ表示する --}}
    {{-- この2つを組み合わせることで「ログインしていて、かつ管理者でない人」だけに表示できる --}}
    @auth
        @cannot('admin-only')
            <div style="margin-top: 16px; padding: 12px; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px;">
                一般ユーザーとしてログインしています
            </div>
        @endcannot
    @endauth
@endsection
*/

/**
 * 【ブラウザで確認する】
 *
 * http://localhost:8080/home にアクセスして以下の3パターンを確認してください：
 *
 * ①ログアウト状態
 *   → 管理者メニューも一般ユーザーメッセージも表示されない
 *
 * ②管理者（is_admin=1）でログイン
 *   → 「⭐ 管理者メニュー（管理者だけに見えています）」が表示される
 *
 * ③一般ユーザー（is_admin=0）でログイン
 *   → 「一般ユーザーとしてログインしています」が表示される
 *   → register画面（localhost:8080/register）から新規登録したユーザーは
 *     is_adminが0（デフォルト値）なので一般ユーザーとして扱われます
 *
 * 【パスワードを忘れた場合のリセット方法】
 */

// tinkerを起動してください：
// docker compose exec -e XDG_CONFIG_HOME=/tmp php php artisan tinker

// パスワードをリセットしてください（以下のコードをコピーして実行してください）：
// $user = User::find(1); $user->password = bcrypt('password123'); $user->save();

/**
 * → true と表示されればパスワードが「password123」にリセットされます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【第2部：学習中に出た質問と回答】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. Gate・Policyは実務では必要か？
 * A1. 必須です。
 *     「ログインしている人なら誰でも何でもできる」アプリは実務では存在しません。
 *     「自分のデータしか編集・削除できない」という制御は最低限の要件です。
 *     GodeVenでも確実に必要になる知識です。
 *
 * ---
 *
 * Q2. git status と git status -u の違いは何か？
 * A2. - git status   = 未追跡ファイルはフォルダ単位でまとめて表示（src/day7/）
 *     - git status -u = 未追跡ファイルをファイル単位で個別に表示
 *                       （src/day7/day7_step7-01_gate_basics.php）
 *     -u は「untracked（アントラックド）= 未追跡」の略です。
 *     フォルダの中に何があるか確認したいときに使います。
 *
 * ---
 *
 * Q3. git statusで「modified」と表示されるのはなぜか？
 * A3. modified（モディファイド）= 「変更済み」という意味です。
 *     Gitが管理しているファイル（過去にコミット済みのファイル）が
 *     変更されたときに表示されます。
 *
 *     【UntrackedとModifiedの違い】
 *     - Untracked = Gitがまだ知らない新しいファイル
 *     - Modified  = Gitが知っているファイルが変更された
 *
 * ---
 *
 * Q4. tinkerを起動するコマンドに「-e XDG_CONFIG_HOME=/tmp」が必要な理由は？
 * A4. tinkerは設定ファイルを「/.config/psysh」というフォルダに保存しようとしますが、
 *     Dockerコンテナ内ではこのフォルダへの書き込み権限がないためエラーになります。
 *     -e XDG_CONFIG_HOME=/tmp を付けることで、
 *     設定の保存場所を書き込みOKな/tmpフォルダに変更できます。
 *
 * ---
 *
 * Q5. @cannotだけだと、ログインしていない状態でも表示されてしまうのはなぜか？
 * A5. @cannotは「このGateがNGの場合」に表示します。
 *     ログインしていない場合、Gateは判定できないため「NG」として扱われます。
 *     結果として、ログインしていない人にも表示されてしまいます。
 *
 *     解決策：@authと組み合わせる
 *     @auth（ログインしている場合）の中に@cannotを書くことで、
 *     「ログインしていて、かつ管理者でない人」だけに表示できます。
 *
 * ---
 *
 * Q6. register画面から登録したユーザーは一般ユーザーになるか？
 * A6. はい、なります。
 *     is_adminカラムのデフォルト値は0（一般ユーザー）なので、
 *     register画面から登録したユーザーは全員is_admin=0で登録されます。
 *
 *     管理者にするにはtinkerで以下を実行してください：
 */

// tinkerを起動してください：
// docker compose exec -e XDG_CONFIG_HOME=/tmp php php artisan tinker

// 対象ユーザーのIDを確認してから管理者に変更してください（以下をコピーして実行してください）：
// $user = User::find(IDの番号); $user->is_admin = 1; $user->save();

/**
 * → true と表示されれば管理者への変更が完了です
 * → IDの番号はphpMyAdminのusersテーブルで確認できます
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
 *    実務で最もよく使われる書き方
 *
 * abort(403)（アボート フォー・オー・スリー）
 * └─ 「アクセス禁止」エラーページを表示して処理を中断する
 *    403 = Forbidden（フォービドゥン）= アクセス禁止
 *
 * @can（アットキャン）
 * └─ BladeでGateがOKの場合だけ表示するディレクティブ
 *
 * @cannot（アットキャノット）
 * └─ BladeでGateがNGの場合だけ表示するディレクティブ
 *
 * @auth（アットオース）
 * └─ ログインしている場合だけ処理するディレクティブ
 *    @cannotと組み合わせて「ログインしていて、かつ管理者でない人」を判定できる
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
 *
 * modified（モディファイド）
 * └─ git statusで表示される「変更済み」を意味する言葉
 *
 * XDG_CONFIG_HOME（エックスディージー コンフィグ ホーム）
 * └─ 設定ファイルの保存場所を指定する環境変数
 *    /tmpを指定することでDockerコンテナ内の権限問題を回避できる
 *
 * HTTPステータスコード（エイチティーティーピー ステータスコード）
 * └─ サーバーからブラウザへの「結果報告」の番号
 *    200=正常、403=アクセス禁止、404=見つからない、500=サーバーエラー
 */
