<?php
/**
 * 📘 Day9 教材（Step9-01：Bladeレイアウトの仕組みを理解する）
 *
 * この教材では「ページの共通部分をまとめて管理する仕組み」を学びます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【現在の状況】
 * expenses（エクスペンシーズ）の3ファイルを見てみると、
 * それぞれに <!DOCTYPE html> から </html> まで全部書いてあります。
 *
 * つまり「ヘッダー」「フッター」が3つのファイルに同じ内容で書いてある状態です。
 *
 * 【問題点】
 * もしヘッダーのデザインを変えたくなったら……
 * → index.blade.php を修正
 * → create.blade.php も修正
 * → edit.blade.php も修正
 * と、同じ作業を3回しなければなりません。
 *
 * ページが10個、20個になったら……大変です。
 *
 * 【解決策：レイアウト（共通の枠）を使う】
 * すでに layouts/app.blade.php という「共通の枠」が存在しています。
 * home.blade.php や tasks/index.blade.php はすでにこれを使っています。
 *
 * Day9 では「この仕組みがどう動くのか」を理解した上で、
 * expenses系のファイルも同じ仕組みに移行します。
 *
 * 【今日学ぶ3つのキーワード】
 * 1. @extends（アットエクステンズ） = 「この枠を使います」という宣言
 * 2. @section（アットセクション）  = 「ここに内容を入れます」という宣言
 * 3. @yield（アットイールド）       = 「ここに内容を差し込んでください」という場所の指定
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📋 【このStep9-01でやること】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * コードを書く作業はありません。
 * 既存のファイルを「読んで理解する」確認作業です。
 *
 * 【確認1：home.blade.php をVSCodeで開く】
 * resources/views/home.blade.php
 *
 * 以下の3行を見つけてください：
 * ① @extends('layouts.app')          → 「この枠を使います」
 * ② @section('title', 'ホームページ') → 「タイトルを指定」
 * ③ @section('content')〜@endsection → 「本文を指定」
 *
 * 【確認2：tasks/index.blade.php をVSCodeで開く】
 * resources/views/tasks/index.blade.php
 *
 * home.blade.php と同じ構造になっていることを確認してください。
 *
 * 【確認3：expenses/index.blade.php をVSCodeで開く】
 * resources/views/expenses/index.blade.php
 *
 * こちらは <!DOCTYPE html> から始まっていて、
 * @extends を使っていないことを確認してください。
 * → Step9-03でこれを修正します
 *
 * 【ブラウザで確認】
 * Docker を起動した状態で http://localhost:8080/home にアクセスし、
 * ログイン後にホームページを表示してください。
 * ヘッダー（Laravel練習アプリ）とフッター（© 2026 KOH's Laravel-practice）が
 * 表示されていれば、layouts/app.blade.php が正しく機能しています。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. レイアウトとは何か？】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【たとえ話：雑誌のテンプレート】
 *
 * 雑誌を想像してください。
 * 毎号、「表紙のデザイン」「ページ番号の位置」「奥付（編集後記のページ）」は同じです。
 * 変わるのは「本文の内容」だけです。
 *
 * Bladeのレイアウトも同じです：
 * - 変わらない部分 → layouts/app.blade.php（共通の枠）
 * - ページごとに変わる部分 → 各Bladeファイル（本文）
 *
 * 【現在の layouts/app.blade.php の構造】
 *
 * <!DOCTYPE html>
 * <html lang="ja">
 * <head>
 *     <title>@yield('title', 'Laravel App')</title>  ← ここにタイトルが入る
 * </head>
 * <body>
 *     <header>...</header>
 *
 *     <main>
 *         @yield('content')  ← ここに各ページの本文が入る
 *     </main>
 *
 *     <footer>...</footer>
 * </body>
 * </html>
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. @extends・@section・@yieldの関係】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【たとえ話：額縁と絵】
 *
 * layouts/app.blade.php = 額縁（がくぶち）
 * 各ページのBladeファイル = 額縁に入れる絵
 *
 * 【@yield と @section は「予約と答え合わせ」】
 *
 * @yield は「ここは後で決めます」という予約マークです。
 * 'title' はその予約に付けたニックネームです。
 *
 * @section は「さっきの予約、これで決めます」という答え合わせです。
 *
 * 例：
 * layouts/app.blade.php 側（予約する）：
 *   @yield('title', 'Laravel App')
 *   → 「titleというニックネームを付けた予約場所。
 *      誰も答えなければ'Laravel App'を使います」
 *
 * home.blade.php 側（答え合わせする）：
 *   @section('title', 'ホームページ')
 *   → 「titleの答えは'ホームページ'です」
 *
 * 【動作の流れ】
 * ブラウザがページを表示するとき：
 * 1. home.blade.php を読む
 * 2. @extends('layouts.app') を発見
 * 3. layouts/app.blade.php（額縁）を取得
 * 4. @yield('title') の予約場所に @section('title') の答えを入れる
 * 5. @yield('content') の予約場所に @section('content')〜@endsection の答えを入れる
 * 6. 完成したHTMLをブラウザに渡す
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. 実際のコードで確認する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【layouts/app.blade.php（額縁）】
 *
 * <!DOCTYPE html>
 * <html lang="ja">
 * <head>
 *     <meta charset="UTF-8">
 *     <title>@yield('title', 'Laravel App')</title>
 * </head>
 * <body>
 *     <header>
 *         <h1>Laravel練習アプリ</h1>
 *         <hr>
 *         @auth
 *             <form method="POST" action="{{ route('logout') }}" style="display:inline;">
 *                 @csrf
 *                 <button type="submit">ログアウト</button>
 *             </form>
 *         @endauth
 *     </header>
 *
 *     <main>
 *         @if (session('status'))
 *             <div>{{ session('status') }}</div>
 *         @endif
 *         @yield('content')   ← 予約場所
 *     </main>
 *
 *     <footer>
 *         <hr>
 *         <small>&copy; {{ date('Y') }} KOH's Laravel-practice</small>
 *     </footer>
 * </body>
 * </html>
 *
 * 【home.blade.php（絵）】
 *
 * @extends('layouts.app')          ← 「この額縁を使います」
 *
 * @section('title', 'ホームページ')  ← titleの答え合わせ
 *
 * @section('content')              ← contentの答え合わせ（開始）
 *     <p>こんにちは、KOH！</p>
 *     @can('admin-only')
 *         <div>⭐ 管理者メニュー</div>
 *     @endcan
 * @endsection                      ← contentの答え合わせ（終了）
 *
 * 【ブラウザに届くHTML（合体した結果）】
 *
 * <!DOCTYPE html>
 * <html lang="ja">
 * <head>
 *     <title>ホームページ</title>     ← @section('title') の答えが入った
 * </head>
 * <body>
 *     <header>
 *         <h1>Laravel練習アプリ</h1>
 *         ...（共通ヘッダー）
 *     </header>
 *     <main>
 *         <p>こんにちは、KOH！</p>   ← @section('content') の答えが入った
 *     </main>
 *     <footer>
 *         ...（共通フッター）
 *     </footer>
 * </body>
 * </html>
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. @section の2つの書き方】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * @section には2つの書き方があります。
 *
 * 【書き方1：1行で書く（短い内容の場合）】
 * @section('title', 'ホームページ')
 *
 * → 「titleの答えは'ホームページ'」
 * → 1行で完結するので @endsection は不要
 * → タイトルのような短い文字列に使う
 *
 * 【書き方2：ブロックで書く（長い内容の場合）】
 * @section('content')
 *     <h2>タスク一覧</h2>
 *     <p>ここに長いHTMLが続く...</p>
 *     @foreach ($tasks as $task)
 *         <li>{{ $task->title }}</li>
 *     @endforeach
 * @endsection
 *
 * → 「contentの答えは @section〜@endsection の間の内容」
 * → 複数行にわたるHTMLに使う
 * → 最後に @endsection（アットエンドセクション）が必要
 *
 * 【使い分けの判断基準】
 * 1行で書ける → 書き方1（@section('name', '値')）
 * 複数行になる → 書き方2（@section〜@endsection）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. layoutsフォルダの意味】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * @extends('layouts.app') の「layouts.app」は何を指しているのでしょうか？
 *
 * 【ドット（.）はスラッシュ（/）の代わり】
 * layouts.app = resources/views/layouts/app.blade.php
 *
 * ドット（.）がフォルダの区切りを表しています：
 * layouts → layoutsフォルダ
 * app     → app.blade.php というファイル
 *
 * 同様に：
 * 'expenses.index' → resources/views/expenses/index.blade.php
 * 'tasks.create'   → resources/views/tasks/create.blade.php
 *
 * 【layouts フォルダの役割】
 * 「レイアウト専用のフォルダ」です。
 * 共通の枠（テンプレート）をここにまとめて管理します。
 *
 * 将来、「管理者用レイアウト」「モバイル用レイアウト」など
 * 複数のレイアウトが必要になったとき、ここに追加していきます：
 *
 * resources/views/layouts/
 * ├── app.blade.php      ← 現在使っている共通レイアウト
 * ├── admin.blade.php    ← 将来の管理者用レイアウト（例）
 * └── guest.blade.php    ← 将来のゲスト用レイアウト（例）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【6. @yield のデフォルト値】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * layouts/app.blade.php を見ると：
 * @yield('title', 'Laravel App')
 *
 * 第2引数（だいにひきすう）の 'Laravel App' はデフォルト値です。
 *
 * 【デフォルト値とは？】
 * 「答え合わせがなかった場合に代わりに使う値」です。
 *
 * 【3つのパターンで整理】
 *
 * パターン①：@section('title', 'ホームページ') と書いた場合
 *   → タイトルは「ホームページ」
 *
 * パターン②：@section('title', ...) を書かなかった場合
 *             ＆ @yield('title', 'Laravel App') とデフォルト値あり
 *   → タイトルは「Laravel App」（デフォルト値が使われる）
 *
 * パターン③：@section('title', ...) を書かなかった場合
 *             ＆ @yield('title') とデフォルト値なし
 *   → タイトルが空白になる
 *
 * 【なぜデフォルト値をわざわざ書くのか？】
 * 「答え合わせを書き忘れても、最低限サイト名は表示しておきたい」という
 * 保険のためです。
 * タイトルが空白のページはユーザーにとって不便なので、
 * 'Laravel App' という最低限の表示を保証しています。
 *
 * 【@yield('content') にデフォルト値がない理由】
 * 各ページの本文は必ず書くものなので、保険は不要です。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ❓ 【よくある質問 Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. @extends はどこに書きますか？
 * A1. ファイルの一番上に書きます。
 *     <!DOCTYPE html> などは書かなくてよいです（額縁側に書いてあるため）。
 *     ファイルの先頭行が @extends('layouts.app') になります。
 *
 * Q2. @endsection を書き忘れるとどうなりますか？
 * A2. Laravelがエラーを出します。
 *     「@section を閉じていません」という内容のエラーです。
 *     @section('content') を書いたら、必ず @endsection を書く習慣をつけましょう。
 *
 * Q3. layouts/app.blade.php を直接ブラウザで開けますか？
 * A3. 開けません。レイアウトファイルは「枠」だけで、
 *     単体では不完全なHTMLです。
 *     必ず @extends を使うページファイルを経由して表示されます。
 *
 * Q4. @yield と @section の名前（'title'や'content'）は自由に決められますか？
 * A4. はい、自由に決められます。
 *     ただし @yield('title') と @section('title') の名前は一致させる必要があります。
 *     名前が違うと内容が差し込まれません。
 *     一般的に 'title'・'content'・'scripts'・'styles' などがよく使われます。
 *
 * Q5. 複数の @section を1つのファイルに書けますか？
 * A5. はい、書けます。
 *     例えば：
 *     @section('title', 'ホームページ')
 *     @section('content')
 *         <p>本文</p>
 *     @endsection
 *     のように、複数の @section を書いてそれぞれ別の @yield に答え合わせできます。
 *
 * Q6. expenses系のファイルは今なぜ @extends を使っていないのですか？
 * A6. Day3でexpensesのCRUD機能を作ったとき、
 *     当時の目標は「フォームの送信・保存・削除が動くこと」でした。
 *     そのためHTMLの共通化は後回しにして、まず動く状態を作ることを優先しました。
 *     実際の開発でも「まず動かす → 後で整理する」という順番はよくあります。
 *     Day9のStep9-03でその「整理」を行います。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📚 【用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * レイアウト（Layout）
 * └─ ページの共通部分（ヘッダー・フッターなど）をまとめた「枠」のファイル
 * └─ resources/views/layouts/ フォルダに置く
 *
 * @extends（アットエクステンズ）
 * └─ 「このレイアウト（枠）を使います」という宣言
 * └─ @extends('layouts.app') と書く
 * └─ ファイルの一番上に書く
 *
 * @yield（アットイールド）
 * └─ レイアウト側に書く「予約場所」
 * └─ @yield('content') = 「ここに各ページの本文を入れてください」
 * └─ @yield('title', 'Laravel App') = デフォルト値付きの予約場所
 *
 * @section（アットセクション）
 * └─ 各ページ側に書く「答え合わせ」
 * └─ @section('title', '値') = 1行で書く形式（短い内容用）
 * └─ @section('content')〜@endsection = ブロックで書く形式（長い内容用）
 *
 * @endsection（アットエンドセクション）
 * └─ @section のブロック形式の終わりを示す
 * └─ @section('content') と必ずセットで使う
 *
 * デフォルト値
 * └─ 「答え合わせがなかった場合に代わりに使う値」
 * └─ @yield('title', 'Laravel App') の 'Laravel App' がデフォルト値
 *
 * テンプレート（Template）
 * └─ 「型・雛形」のこと
 * └─ Bladeのレイアウトファイルはテンプレートの一種
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 💬 【第2部：学習中の質問と回答】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q. @yield('title', 'Laravel App') のデフォルト値について、
 *    @yield('title') と書いた場合との違いは何ですか？
 *
 * A. @yield('title') と書いた場合（デフォルト値なし）：
 *    → @section('title', ...) を書かなかったとき、タイトルが空白になります。
 *
 *    @yield('title', 'Laravel App') と書いた場合（デフォルト値あり）：
 *    → @section('title', ...) を書かなかったとき、「Laravel App」が表示されます。
 *
 *    デフォルト値は「書き忘れても最低限これを表示しておく」という保険です。
 *
 * ---
 *
 * Q. expenses系のファイルに @extends がないのはなぜですか？
 *
 * A. Day3でexpensesのCRUD機能を作ったとき、
 *    まず「フォームが動くこと」を優先しました。
 *    HTMLの共通化（レイアウト化）は「後でまとめてやる作業」として
 *    Day9に持ち越しています。
 *    Step9-03でこれを整理します。
 */