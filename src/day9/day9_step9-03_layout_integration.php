<?php

/**
 * 📘 Day9 教材（Step9-03：expenses系ビューをレイアウトに統合する）【統合版】
 *
 * この教材では、expenses（エクスペンシーズ）系の3つのビューファイルを
 * Step9-01で作ったレイアウトに統合する方法を学びます。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【現在の状態（問題点）】
 *
 * expenses系の3ファイルは、今こんな状態です：
 *
 * index.blade.php  → x-alertやx-cardは使っているが、@extendsがまだ
 * create.blade.php → @extendsもx-コンポーネントもまだ
 * edit.blade.php   → @extendsもx-コンポーネントもまだ
 *
 * 3ファイルそれぞれに <!DOCTYPE html> から </html> まで
 * 全部書いているので、ヘッダーやフッターを変えたいとき
 * 3カ所を全部直さないといけない状態です。
 *
 * 【Step9-03で目指すゴール】
 *
 * 3ファイルすべてを @extends('layouts.app') 形式にする
 * → 共通部分（<!DOCTYPE html>〜</html>の骨格）を layouts/app.blade.php に任せる
 * → 各ファイルは「自分の画面固有の内容」だけを書く
 *
 * create・edit のエラー表示も x-alert コンポーネントに統一する
 *
 * 【たとえ話】
 *
 * 今の状態 = お弁当を毎回「ご飯・おかず・デザート・箸・ナプキン」を
 *             一から全部用意している状態
 *
 * ゴールの状態 = お弁当箱（layouts/app.blade.php）を1つ用意して、
 *               中のおかず（各ビュー固有のコンテンツ）だけ入れ替える状態
 *
 * 【今日の作業量】
 * ファイルを3つ修正するだけ。新しいファイルは作りません。
 * パターンが同じなので、1つ理解すれば残りは簡単です。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. @extendsとは？（復習）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * @extends（アットエクステンズ）= 「〇〇のレイアウトを使います」という宣言
 *
 * 書き方：
 * @extends('layouts.app')
 *
 * 意味：
 * 「このファイルは layouts/app.blade.php という枠（わく）を使います」
 *
 * セットで使う記法：
 *
 * @section('title', 'ページタイトル')
 * → レイアウトの @yield('title') の場所にタイトルを流し込む
 *
 * @section('content')
 *     ここにページ固有のHTMLを書く
 * @endsection
 * → レイアウトの @yield('content') の場所に流し込む
 *
 * 【記号の読み方】
 * @extends   → アットエクステンズ
 * @section   → アットセクション
 * @endsection → アットエンドセクション
 * @yield    → アットイールド（Step9-01でlayouts/app.blade.phpに書いたもの）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. 変更前・変更後の対比】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 変更前（現在のcreate.blade.php の先頭と末尾）：
 *
 * <!DOCTYPE html>
 * <html lang="ja">
 * <head>
 *     <meta charset="UTF-8">
 *     <title>支出を追加</title>
 * </head>
 * <body>
 *     ... ここに画面固有のHTML ...
 * </body>
 * </html>
 *
 * ↓
 *
 * 変更後：
 *
 * @extends('layouts.app')
 * @section('title', '支出を追加')
 *
 * @section('content')
 *     ... ここに画面固有のHTMLだけを残す ...
 * @endsection
 *
 * 【ポイント】
 * 削除するもの：
 *   <!DOCTYPE html>〜<head>〜</head>〜<body>〜</body>〜</html>
 *   （この骨格は layouts/app.blade.php が担当するので不要）
 *
 * 残すもの：
 *   画面の中身（見出し・フォーム・テーブルなど）だけ
 *
 * 追加するもの：
 *   @extends('layouts.app')
 *   @section('title', 'ページタイトル')
 *   @section('content') と @endsection
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. 実際の修正内容：3ファイル分】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 以下の「変更後のコード」をそれぞれのファイルに貼り付けてください。
 * （ファイルの中身を全選択→削除→貼り付け）
 *
 * ──────────────────────────────────────────
 * 【ファイル1】resources/views/expenses/index.blade.php
 * ──────────────────────────────────────────
 *
 * ポイント：
 * - x-alert と x-card はすでに使っていた → そのまま残す
 * - <!DOCTYPE html>〜<body> などの骨格を削除して @extends に置き換える
 */

// 以下のコードを index.blade.php の中身として貼り付けてください：

/*

@extends('layouts.app')
@section('title', '支出一覧')

@section('content')
    <h1>支出一覧</h1>

    <a href="{{ route('expenses.create') }}">新しい支出を追加する</a>

    <x-alert type="success" :message="session('success')" />

    @if ($expenses->isEmpty())
        <p>支出データがまだありません。</p>
    @else
        <x-card>
            <table border="1" cellpadding="8" cellspacing="0">
                <tr>
                    <th>日付</th>
                    <th>カテゴリ</th>
                    <th>金額</th>
                    <th>説明</th>
                    <th>タグ</th>
                    <th>画像</th>
                    <th>操作</th>
                </tr>
                @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->spent_at }}</td>
                        <td>{{ $expense->category }}</td>
                        <td>{{ number_format($expense->amount) }}円</td>
                        <td>{{ $expense->description }}</td>
                        <td>
                            @foreach ($expense->tags as $tag)
                                <span style="background: #dbeafe; padding: 2px 8px; border-radius: 4px; margin-right: 4px;">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            @if ($expense->image_path)
                                <img src="{{ Storage::url($expense->image_path) }}" alt="支出画像" width="100">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('expenses.edit', $expense) }}">編集</a>
                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('削除しますか？')">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </x-card>
    @endif
@endsection

*/

/**
 * ──────────────────────────────────────────
 * 【ファイル2】resources/views/expenses/create.blade.php
 * ──────────────────────────────────────────
 *
 * ポイント：
 * - エラー表示を x-alert に置き換える
 *   変更前：@if ($errors->any()) ... @endif（独自のHTML）
 *   変更後：<x-alert type="error" :errors="$errors" />
 */

// 以下のコードを create.blade.php の中身として貼り付けてください：

/*

@extends('layouts.app')
@section('title', '支出を追加')

@section('content')
    <h1>支出を追加する</h1>

    <x-alert type="error" :errors="$errors" />

    <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label>カテゴリ</label><br>
            <input type="text" name="category" value="{{ old('category') }}">
            @error('category')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>金額（円）</label><br>
            <input type="number" name="amount" value="{{ old('amount') }}">
            @error('amount')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>説明（任意）</label><br>
            <textarea name="description">{{ old('description') }}</textarea>
            @error('description')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>日付</label><br>
            <input type="date" name="spent_at" value="{{ old('spent_at') }}">
            @error('spent_at')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>タグ（複数選択可）</label><br>
            @foreach ($tags as $tag)
                <label style="margin-right: 12px;">
                    <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}">
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>

        <div>
            <label for="image">画像（任意）</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <button type="submit">登録する</button>
    </form>
@endsection

*/

/**
 * ──────────────────────────────────────────
 * 【ファイル3】resources/views/expenses/edit.blade.php
 * ──────────────────────────────────────────
 *
 * ポイント：create.blade.php と同じパターンで変更します。
 */

// 以下のコードを edit.blade.php の中身として貼り付けてください：

/*

@extends('layouts.app')
@section('title', '支出を編集')

@section('content')
    <h1>支出を編集する</h1>

    <x-alert type="error" :errors="$errors" />

    <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label>カテゴリ</label><br>
            <input type="text" name="category" value="{{ old('category', $expense->category) }}">
            @error('category')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>金額（円）</label><br>
            <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}">
            @error('amount')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>説明（任意）</label><br>
            <textarea name="description">{{ old('description', $expense->description) }}</textarea>
            @error('description')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>日付</label><br>
            <input type="date" name="spent_at" value="{{ old('spent_at', $expense->spent_at) }}">
            @error('spent_at')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>画像</label><br>

            @if ($expense->image_path)
                <p>現在の画像：</p>
                <img src="{{ Storage::url($expense->image_path) }}" alt="現在の画像" width="150">
                <br>
                <label>
                    <input type="checkbox" name="delete_image" value="1">
                    この画像を削除する
                </label>
                <br>
            @endif

            <p>新しい画像を選ぶ（任意）：</p>
            <input type="file" name="image" accept="image/*">
            @error('image')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>タグ（複数選択可）</label><br>
            @foreach ($tags as $tag)
                <label style="margin-right: 12px;">
                    <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" @checked(in_array($tag->id, $selectedTagIds ?? []))>
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>

        <button type="submit">更新する</button>
    </form>

    <a href="{{ route('expenses.index') }}">一覧に戻る</a>
@endsection

*/

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. 動作確認の手順】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 3ファイルの修正が終わったら、ブラウザで確認します。
 *
 * 確認項目：
 *
 * 1. 支出一覧ページ（/expenses）が表示されるか
 *    → layouts/app.blade.php のヘッダーが出ていればOK
 *
 * 2. 新規追加ページ（/expenses/create）が表示されるか
 *    → フォームが正常に表示されていればOK
 *
 * 3. 編集ページ（/expenses/{id}/edit）が表示されるか
 *    → フォームが正常に表示されていればOK
 *
 * 4. バリデーションエラーが出るか確認
 *    → 新規追加フォームでカテゴリを空にして「登録する」を押す
 *    → x-alert のエラー表示が出ればOK
 *
 * 5. 登録成功メッセージが出るか確認
 *    → 正しく登録したあと一覧ページで x-alert の成功表示が出ればOK
 */

// Dockerが起動していない場合は先に起動してください：
// docker compose up -d

// ブラウザで以下のURLを確認してください：
// http://localhost/expenses

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📖 【5. 用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * @extends（アットエクステンズ）
 * └─ 「このファイルは○○のレイアウトを使います」という宣言
 *
 * @section（アットセクション）
 * └─ 「ここが○○という名前のブロックの中身です」という区切り
 *
 * @endsection（アットエンドセクション）
 * └─ @section の終わり
 *
 * @yield（アットイールド）
 * └─ レイアウト側に書く。「ここに○○という名前のブロックを流し込む」という指定
 *
 * layouts/app.blade.php（レイアウト・アップ・ブレード・ピーエイチピー）
 * └─ 全ページ共通の「骨格ファイル」。<!DOCTYPE html>〜</html>はここだけに書く
 *
 * x-alert（エックスアラート）
 * └─ コンポーネント。成功・エラーのメッセージ表示を担当する部品
 *
 * x-card（エックスカード）
 * └─ コンポーネント。コンテンツをカード状に囲む部品
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ❓ 【6. よくある疑問 Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. @extends を書かないとどうなりますか？
 * A1. レイアウトを使わず、そのファイルの内容だけが表示されます。
 *     つまり <!DOCTYPE html> がない「不完全なHTML」になってしまいます。
 *     ブラウザは表示できますが、正しいHTML構造ではありません。
 *
 * Q2. @section('title', '支出一覧') と
 *     @section('title') / @endsection の2通りの書き方があるのはなぜ？
 * A2. 短い内容（1行）は @section('title', 'テキスト') と1行で書けます。
 *     長い内容（複数行）は @section('title') 〜 @endsection の形式を使います。
 *     ページタイトルは1行なので短い書き方が使えます。
 *
 * Q3. tasks系（一覧・作成・編集・詳細）はStep9-03で対応しますか？
 * A3. 今回はexpenses系3ファイルのみです。
 *     tasks系はStep9-04以降で対応する予定です。
 *
 * Q4. x-alert に type="error" を渡すとどうなりますか？
 * A4. alert.blade.php の中でtype変数を受け取り、
 *     "error" なら赤い枠・文字でエラーメッセージを表示します。
 *     "success" なら緑色の成功メッセージを表示します。
 *
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【第2部：学習中に出た質問と回答】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q5. alert.blade.php を修正するA案と、エラー表示を直書きのままにするB案、
 *     GodeVenにとって良いのはどちらですか？
 *
 * A5. A案（alert.blade.phpを拡張する）が推奨です。理由は3つです。
 *
 *     1. GodeVenでも同じ問題が必ず起きる
 *        StemmeやLokaltでもフォームを作れば、成功メッセージとバリデーション
 *        エラーの両方が必要になります。今学んでおくとGodeVenで迷わず実装できます。
 *
 *     2. 「1つのコンポーネントが複数の用途に対応する」設計の理解
 *        $message（1つの文字列）と$errors（複数のエラー）を同じコンポーネントで
 *        扱う設計は、実務でよく使うパターンです。
 *
 *     3. B案は技術的負債になる
 *        「コンポーネントがあるのに一部だけ直書き」という中途半端な状態が残り、
 *        GodeVen開発で後から直す手間が増えます。
 *
 * Q6. 今回の作業は、バリデーション処理もコンポーネントに移したということですか？
 *
 * A6. いいえ、コンポーネントに移したのは「表示だけ」です。
 *     バリデーション（入力チェック）の処理自体はExpenseRequest.phpがやっています。
 *
 *     整理するとこうなります：
 *     - 入力チェック（バリデーション）   → ExpenseRequest.php（変わらない）
 *     - エラー結果の受け取り          → Controller → Bladeへ渡す（変わらない）
 *     - エラーの「表示」             → x-alertコンポーネントに統一（ここを変えた）
 *     - 成功メッセージの「表示」       → x-alertコンポーネント（Step9-02で対応済み）
 *
 * Q7. 今回やったことを一言でまとめると？
 *
 * A7. 「各ページの固有コンテンツ以外はすべて共通化した」ということです。
 *
 *     共通化した内容の整理：
 *     - HTML骨格（DOCTYPE・head・body）   → layouts/app.blade.php
 *     - ヘッダー・フッター            → layouts/app.blade.php
 *     - 成功・エラーメッセージの表示スタイル → x-alertコンポーネント
 *     - テーブルの枠                → x-cardコンポーネント
 *     - 各ページ固有のコンテンツ       → 各bladeファイルに残す（変えない）
 *
 *     この「固有部分だけを各ファイルに書き、それ以外は共通部品に任せる」
 *     という考え方が、GodeVenでも全ページに適用する設計思想になります。
 */
