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
<div x-data='{ "selectedFruits": @json($fruits, JSON_UNESCAPED_UNICODE), "selected": "" }'>
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