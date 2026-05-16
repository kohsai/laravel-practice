<?php

/**
 * 📘 Step11 教材（step11_blade_alpine.php）
 * BladeとAlpine.jsの組み合わせ方
 *
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step00〜Step10でAlpine.jsの動きをHTMLファイル単体で学びました。
 * Step11では「本番と同じ環境」＝Laravel＋Blade（ブレード）の中で
 * Alpine.jsを動かす感覚を掴みます。
 *
 * 【今日の核心テーマ】
 * ① Bladeの {{ }} と Alpine.jsの x-text は「どこで処理するか」が違う
 * ② @if・@foreach（PHP処理）とAlpine.jsのディレクティブ（ブラウザ処理）の使い分け
 * ③ Alpine.jsのCDNをどこに書くか（layouts/app.blade.php）
 * ④ BladeファイルにAlpine.jsのディレクティブを実際に書く感覚
 *
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🏗 【Step11の作業全体像】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 作るもの：
 * 1. ルート追加（routes/web.php）
 * 2. コントローラー作成（Step11Controller.php）
 * 3. Bladeビュー作成（step11.blade.php）
 * 4. Alpine.jsのCDN追加（layouts/app.blade.php）
 *
 * アクセスURL：http://localhost/step11
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📖 【STEP A】処理の場所を理解する
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Laravelでページを表示するとき、処理は「2段階」で行われます。
 *
 * 【第1段階：サーバー側（PHPが処理）】
 * ブラウザにHTMLを送り出す「前」にPHPが処理します。
 *
 * Bladeの記法：
 *   {{ $name }}      ← PHPの変数をHTMLに埋め込む
 *   @if(条件)        ← PHPで条件分岐
 *   @foreach(配列)   ← PHPでループ
 *
 * たとえ話：
 * 料理人（PHP）が厨房で料理を仕上げてから、
 * お客さん（ブラウザ）のテーブルに運ぶイメージ。
 * お客さんが見るのは「完成した料理（HTML）」だけ。
 *
 * 【第2段階：ブラウザ側（JavaScriptが処理）】
 * HTMLがブラウザに届いた「後」にJavaScriptが処理します。
 *
 * Alpine.jsの記法：
 *   x-text="name"    ← JSのデータをHTMLに表示
 *   x-show="open"    ← JSで表示・非表示を切り替え
 *   x-for="item in list" ← JSでループ
 *
 * たとえ話：
 * テーブルに届いた後、お客さん自身が
 * ソースをかけたり、取り分けたりするイメージ。
 * ボタンを押すたびにリアルタイムで変わる。
 *
 * 【使い分けの基準】
 *
 * PHPで処理（Blade）を使う場面：
 * - データベースから取り出したデータを表示する
 * - ログイン状態によって表示を変える
 * - ページを表示する時点で決まっている情報
 *
 * Alpine.jsを使う場面：
 * - ボタンを押したら表示が変わる
 * - 入力した文字をリアルタイムに反映する
 * - タブの切り替えなど、ページ再読み込みなしで動く部分
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🔧 【STEP B】Alpine.jsのCDNを追加する
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【CDN（シーディーエヌ）とは？】
 * インターネット上に置いてあるJavaScriptファイルを
 * URLで読み込む仕組みです。
 * 自分のサーバーにファイルを置かなくても使えます。
 *
 * 【どこに書くか：layouts/app.blade.php】
 *
 * 現在の app.blade.php の末尾付近はこうなっています：
 *
 *   @vite(['resources/css/app.css', 'resources/js/app.js'])
 * </body>
 *
 * Alpine.jsのCDNは @vite() の「前」に追加します。
 * 理由：Alpine.jsはページのHTML要素に対して動くため、
 * HTMLが読み込まれた後かつ @vite() の前に準備が必要です。
 *
 * 【変更後のイメージ】
 *
 *   <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
 *   @vite(['resources/css/app.css', 'resources/js/app.js'])
 * </body>
 *
 * defer（ディファー）= 「HTMLを全部読み終わってから実行してね」という意味。
 * Alpine.jsには必ずdefer属性をつけます（Alpine.js公式の指定）。
 *
 * ✅ 【作業①】layouts/app.blade.php を開いて、
 * @vite() の直前に以下を1行追加してください：
 */

// 以下のコードをコピーして layouts/app.blade.php の @vite() 直前に追加してください：

/*
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
*/

/**
 * 追加後の @vite() 周辺はこうなります：
 *
 *     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
 *     @vite(['resources/css/app.css', 'resources/js/app.js'])
 * </body>
 *
 * 保存（Ctrl+S）したらSTEP Cへ進んでください。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🔧 【STEP C】ルートを追加する
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * routes/web.php に /step11 のルートを追加します。
 *
 * 【既存ルートの末尾に追記するだけでOK】
 * 既存のルートには一切手を加えません。
 *
 * ✅ 【作業②】routes/web.php の末尾に以下を追加してください：
 */

// 以下のコードをコピーして routes/web.php の末尾に追加してください：

/*
use App\Http\Controllers\Step11Controller;
Route::get('/step11', [Step11Controller::class, 'index']);
*/

/**
 * 保存（Ctrl+S）したらSTEP Dへ進んでください。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🔧 【STEP D】コントローラーを作成する
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * コントローラー（Controller）＝ルートとビューをつなぐ「橋」
 *
 * Step11Controller は「Bladeビューに渡すデータ」を用意します。
 * ここで用意したデータがBladeの {{ }} で使えるようになります。
 *
 * ✅ 【作業③】ターミナルで以下を実行してコントローラーを作成してください：
 */

// 以下のコマンドをコピーしてターミナルで実行してください：

/*
docker compose exec php php artisan make:controller Step11Controller
*/

/**
 * 作成されたファイル：
 * src/laravel/app/Http/Controllers/Step11Controller.php
 *
 * 次に、作成されたファイルの中身を以下に書き換えてください。
 * （ファイル全体を選択→削除→貼り付け）
 *
 * ✅ 【作業④】Step11Controller.php の中身を以下に書き換えてください：
 */

// 以下のコードをコピーして Step11Controller.php 全体と置き換えてください：

/*
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Step11Controller extends Controller
{
    public function index()
    {
        // PHPで用意したデータ → Bladeに渡す
        // このデータは「サーバー側（第1段階）」で処理される
        $serverMessage = 'こんにちは！PHPからBladeへのメッセージです。';

        $fruits = ['りんご', 'バナナ', 'みかん'];

        $isLoggedIn = true; // 仮のフラグ（ログイン状態のつもり）

        return view('step11', compact('serverMessage', 'fruits', 'isLoggedIn'));
    }
}
*/

/**
 * 【compact()（コンパクト）とは？】
 * compact('serverMessage', 'fruits', 'isLoggedIn') は
 * ['serverMessage' => $serverMessage, 'fruits' => $fruits, 'isLoggedIn' => $isLoggedIn]
 * と同じ意味です。変数名をそのままキーにして配列を作ってくれます。
 *
 * 保存（Ctrl+S）したらSTEP Eへ進んでください。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🔧 【STEP E】Bladeビューを作成する
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * ここが今日のメインです。
 * BladeとAlpine.jsを「同じファイル」に書いて、
 * 2つの処理の場所の違いを実際に体感します。
 *
 * ✅ 【作業⑤】ターミナルで以下を実行してBladeファイルを作成してください：
 */

// 以下のコマンドをコピーしてターミナルで実行してください：

/*
touch ~/venpro/laravel-practice/src/laravel/resources/views/step11.blade.php
*/

/**
 * ✅ 【作業⑥】step11.blade.php の中身を以下に書き換えてください：
 */

// 以下のコードをコピーして step11.blade.php 全体と置き換えてください：

/*
@extends('layouts.app')

@section('title', 'Step11：BladeとAlpine.js')

@section('content')

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- 【セクション1】PHPが処理する部分（Blade） --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<h2>① PHPが処理する部分（Blade）</h2>

{{-- {{ }} ← PHPの変数をHTMLに埋め込む --}}
{{-- この処理はブラウザに届く「前」にPHPが完了させる --}}
<p>サーバーからのメッセージ：{{ $serverMessage }}</p>

{{-- @if ← PHPで条件分岐 --}}
@if ($isLoggedIn)
    <p>✅ ログイン中です（PHPが判定）</p>
@else
    <p>❌ ログインしていません（PHPが判定）</p>
@endif

{{-- @foreach ← PHPでループ --}}
<ul>
    @foreach ($fruits as $fruit)
        <li>{{ $fruit }}（PHPが出力）</li>
    @endforeach
</ul>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- 【セクション2】Alpine.jsが処理する部分 --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<hr>
<h2>② Alpine.jsが処理する部分（ブラウザ）</h2>

{{-- x-data ← Alpine.jsのデータ定義 --}}
{{-- このデータはブラウザに届いた「後」にJavaScriptが処理する --}}
<div x-data="{ count: 0, open: false, name: '' }">

    {{-- カウンター：ボタンを押すたびにリアルタイムで変わる --}}
    <h3>カウンター</h3>
    <button @click="count++">＋1</button>
    <button @click="count--">－1</button>
    <p>現在のカウント：<span x-text="count"></span></p>

    {{-- トグル：表示・非表示の切り替え --}}
    <h3>トグル表示</h3>
    <button @click="open = !open">メッセージを切り替える</button>
    <p x-show="open">Alpine.jsで表示されています！</p>

    {{-- テキスト入力：リアルタイムに反映 --}}
    <h3>リアルタイム入力</h3>
    <input type="text" x-model="name" placeholder="名前を入力してください">
    <p>こんにちは、<span x-text="name"></span>さん！</p>

</div>

{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
{{-- 【セクション3】BladeとAlpine.jsを組み合わせる --}}
{{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
<hr>
<h2>③ BladeとAlpine.jsを組み合わせる</h2>

{{--
    PHPから渡された $fruits（配列）をAlpine.jsに渡すには
    @json() ディレクティブを使います。

    @json($fruits) は JSON形式に変換してくれます。
    例：["りんご","バナナ","みかん"]

    これをx-dataの中に書くことで、
    PHPのデータをAlpine.jsで使えるようになります。
--}}
<div x-data="{ selectedFruits: @json($fruits), selected: '' }">

    <h3>PHPのデータをAlpine.jsで使う</h3>
    <p>PHPから渡された果物リストをAlpine.jsで選べるようにします。</p>

    <select x-model="selected">
        <option value="">-- 選んでください --</option>
        <template x-for="fruit in selectedFruits" :key="fruit">
            <option :value="fruit" x-text="fruit"></option>
        </template>
    </select>

    <p x-show="selected !== ''">
        あなたが選んだ果物：<strong x-text="selected"></strong>
    </p>

</div>

@endsection
*/

/**
 * 保存（Ctrl+S）したらSTEP Fへ進んでください。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🚀 【STEP F】動作確認
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * ✅ 【作業⑦】Dockerが起動しているか確認してください：
 */

// 以下のコマンドをコピーしてターミナルで実行してください：

/*
docker compose ps
*/

/**
 * php・mysql・nginx が「running」になっていればOKです。
 * 起動していなければ：
 */

// 以下のコマンドをコピーしてターミナルで実行してください：

/*
docker compose up -d
*/

/**
 * ✅ 【作業⑧】ブラウザでアクセスしてください：
 *
 * http://localhost/step11
 *
 * 【確認ポイント】
 * ① PHPが処理する部分
 *   - 「こんにちは！PHPからBladeへのメッセージです。」と表示されるか
 *   - 「✅ ログイン中です（PHPが判定）」と表示されるか
 *   - りんご・バナナ・みかんがリスト表示されるか
 *
 * ② Alpine.jsが処理する部分
 *   - ＋1・－1ボタンを押してカウントが変わるか
 *   - 「メッセージを切り替える」ボタンでメッセージが出たり消えたりするか
 *   - テキストを入力したら「こんにちは、○○さん！」とリアルタイムに変わるか
 *
 * ③ BladeとAlpine.jsを組み合わせた部分
 *   - セレクトボックスにりんご・バナナ・みかんが表示されるか
 *   - 選ぶと「あなたが選んだ果物：○○」と表示されるか
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【まとめ：今日学んだこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【処理の場所の違い】
 *
 * Blade（PHPが処理）           │ Alpine.js（ブラウザが処理）
 * ─────────────────────────── │ ────────────────────────────
 * {{ $variable }}             │ x-text="variable"
 * @if(条件)                   │ x-show="条件"
 * @foreach($array as $item)   │ x-for="item in array"
 * ページ表示時に1回だけ実行    │ ボタン操作のたびにリアルタイム実行
 * DBのデータを表示するのが得意 │ 操作に反応するUIが得意
 *
 * 【@json()ディレクティブ】
 * PHPの配列をAlpine.jsに渡すための橋渡し役。
 * @json($fruits) → ["りんご","バナナ","みかん"] に変換してくれる。
 *
 * 【Alpine.jsのCDNの場所】
 * layouts/app.blade.php の @vite() 直前に書く。
 * defer属性を必ずつける（Alpine.js公式の指定）。
 *
 * 【GodeVen開発での使い分けイメージ】
 * - 投票データをDBから表示する → Blade（PHP）
 * - 投票ボタンを押したらカウントがリアルタイムに増える → Alpine.js
 * - ログイン状態で表示を変える → Blade（PHP）
 * - タブ切り替えUI → Alpine.js
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ❓ 【よくある疑問Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. {{ }} と x-text の見た目が似ているのはなぜですか？
 * A1. どちらも「値をHTMLに表示する」という目的は同じです。
 *     違いは「誰が処理するか」だけ。
 *     {{ }} = PHPがサーバーで処理（届く前に確定）
 *     x-text = JavaScriptがブラウザで処理（届いた後に変えられる）
 *
 * Q2. Alpine.jsのx-dataの中に {{ }} を書いてもいいですか？
 * A2. はい、書けます。セクション③がその例です。
 *     x-data="{ fruits: @json($fruits) }" のように、
 *     PHPの変数をAlpine.jsのデータとして渡せます。
 *     @json() を使うのを忘れずに（配列はそのまま渡せないため）。
 *
 * Q3. @foreach と x-for はどちらを使えばいいですか？
 * A3. データがPHPから来る場合（DBのデータなど）は @foreach で十分です。
 *     ユーザーの操作で動的に変わる場合（フィルタリングなど）は x-for を使います。
 *     GodeVen開発では両方を状況に応じて使い分けます。
 *
 * Q4. CDNのURLにある「3.x.x」は何ですか？
 * A4. Alpine.jsのバージョン番号の「最新版を自動で使う」という指定です。
 *     開発時は具体的なバージョン（例：3.14.1）を指定する方が安全ですが、
 *     学習段階では 3.x.x で問題ありません。
 *
 * Q5. defer はなぜ必要ですか？
 * A5. Alpine.jsは「HTMLの要素」を読んで動く仕組みのため、
 *     HTMLが全部読み込まれてから実行する必要があります。
 *     defer をつけないとHTMLより先に実行されてしまい、
 *     Alpine.jsがHTMLを見つけられずに動かないことがあります。
 */
