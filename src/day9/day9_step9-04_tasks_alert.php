<?php

/**
 * 📘 Day9 教材（Step9-04：tasks系のエラー表示を x-alert に統一する）
 *
 * この教材では「tasks（タスク管理）の2ファイルに残っている
 * 古いエラー表示を、Step9-02で作ったコンポーネントに置き換える」
 * 作業を行います。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと・やること】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【現状の確認】
 *
 * Step9-03で expenses 系（create・edit）は x-alert に統一しました。
 * tasks 系の4ファイルはすでに @extends('layouts.app') 形式になっていましたが、
 * create.blade.php と edit.blade.php の2ファイルに古いエラー表示が残っています。
 *
 * 【古いエラー表示（現在の書き方）】
 *
 * @if ($errors->any())
 *     <div class="alert alert-danger" role="alert">
 *         <p><strong>入力に誤りがあります。</strong></p>
 *         <ul>
 *             @foreach ($errors->all() as $message)
 *                 <li>{{ $message }}</li>
 *             @endforeach
 *         </ul>
 *     </div>
 * @endif
 *
 * 【置き換え後（x-alert を使った書き方）】
 *
 * <x-alert type="error" :errors="$errors" />
 *
 * 【なぜ統一するの？】
 *
 * たとえ話：コーヒーショップのメニューボード
 * - 古い書き方 = 店員それぞれが手書きで「メニュー」を書く
 *   → 人によって書き方がバラバラ、修正が大変
 * - x-alert = 本部が作った「共通メニューボード」を置くだけ
 *   → デザイン変更は1ヶ所（alert.blade.php）だけ直せばOK
 *
 * 今回の作業でアプリ全体のエラー表示が統一されます。
 *
 * 【今日の作業対象】
 * - resources/views/tasks/create.blade.php → エラー表示を x-alert に置換
 * - resources/views/tasks/edit.blade.php   → エラー表示を x-alert に置換
 * - index.blade.php・show.blade.php は変更不要（エラー表示がない）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. x-alert の復習】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step9-02 で作った x-alert コンポーネントを思い出してください。
 *
 * 【alert.blade.php の中身（復習）】
 *
 * @props([
 *     'type'   => 'success',
 *     'message' => null,
 *     'errors'  => null,
 * ])
 *
 * @if ($type === 'success' && $message)
 *     （緑色の成功メッセージを表示）
 * @elseif ($type === 'error' && $errors?->any())
 *     （赤色のエラーメッセージを表示）
 * @endif
 *
 * 【x-alert の使い方（2パターン）】
 *
 * パターン1：成功メッセージ
 * <x-alert type="success" :message="session('success')" />
 *
 * パターン2：バリデーションエラー
 * <x-alert type="error" :errors="$errors" />
 *
 * 今回使うのは「パターン2」です。
 *
 * 【:errors="$errors" の処理の流れ】
 *
 * 登場人物：
 * - $errors     = Laravelが自動で用意する「エラーメモ帳」
 * - x-alert     = エラーを表示する「掲示板係」
 * - :errors="$errors" = メモ帳を掲示板係に渡す「手渡し作業」
 *
 * ① ユーザーがフォームを送信する
 *    タイトルを空のまま「保存」を押した。
 *
 * ② Laravelがバリデーションをチェックする
 *    「タイトルは必須なのに空だ」と気づく。
 *
 * ③ Laravelが $errors にエラーを書き込む
 *    メモ帳に「タイトルは必須です」と自動で書く。この作業はLaravelが勝手にやってくれる。
 *
 * ④ フォームページに戻ってくる
 *    create.blade.php が再び表示される。このとき $errors の中にエラーが入っている。
 *
 * ⑤ :errors="$errors" が実行される
 *    掲示板係（x-alert）に「このメモ帳を見て表示してください」とメモ帳を手渡す。
 *
 * ⑥ x-alert がエラーを画面に表示する
 *    受け取ったメモ帳の内容を見て「入力に誤りがあります」と赤く表示する。
 *
 * 【コロン（:）がない場合との違い】
 * コロンなし → errors="$errors" → 「$errors」という文字列をそのまま渡す
 * コロンあり → :errors="$errors" → $errors の中身（エラー情報）を渡す
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. tasks/create.blade.php の修正】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【修正前（古いエラー表示）】
 *
 * <form action="{{ route('tasks.store') }}" method="POST">
 *     @csrf
 *
 *     {{-- バリデーションエラーの表示ブロック（共通） --}}
 *     @if ($errors->any())
 *         <div class="alert alert-danger" role="alert">
 *             <p><strong>入力に誤りがあります。</strong></p>
 *             <ul>
 *                 @foreach ($errors->all() as $message)
 *                     <li>{{ $message }}</li>
 *                 @endforeach
 *             </ul>
 *         </div>
 *     @endif
 *
 *     <label for="title">タイトル:</label>
 *     ...
 *
 * 【修正後（x-alert を使ったエラー表示）】
 *
 * <form action="{{ route('tasks.store') }}" method="POST">
 *     @csrf
 *
 *     <x-alert type="error" :errors="$errors" />
 *
 *     <label for="title">タイトル:</label>
 *     ...
 *
 * 【変更のポイント】
 * 削除：@if ($errors->any()) 〜 @endif の8行
 * 追加：<x-alert type="error" :errors="$errors" /> の1行
 *
 * {{-- バリデーションエラーの表示ブロック（共通） --}} のコメントも
 * 一緒に削除して構いません（x-alert に役割が移ったため不要です）。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. tasks/edit.blade.php の修正】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * create.blade.php と全く同じ修正です。
 *
 * 【修正前（古いエラー表示）】
 *
 * <form method="POST" action="{{ route('tasks.update', ['task' => $task->id]) }}">
 *     @csrf
 *     @method('PUT')
 *
 *     {{-- バリデーションエラーの表示ブロック（共通） --}}
 *     @if ($errors->any())
 *         <div class="alert alert-danger" role="alert">
 *             <p><strong>入力に誤りがあります。</strong></p>
 *             <ul>
 *                 @foreach ($errors->all() as $message)
 *                     <li>{{ $message }}</li>
 *                 @endforeach
 *             </ul>
 *         </div>
 *     @endif
 *
 *     <div>
 *         <label>タイトル：
 *         ...
 *
 * 【修正後（x-alert を使ったエラー表示）】
 *
 * <form method="POST" action="{{ route('tasks.update', ['task' => $task->id]) }}">
 *     @csrf
 *     @method('PUT')
 *
 *     <x-alert type="error" :errors="$errors" />
 *
 *     <div>
 *         <label>タイトル：
 *         ...
 *
 * 【変更のポイント】
 * create.blade.php と同じです。
 * 削除：@if ($errors->any()) 〜 @endif の8行（+ コメント行）
 * 追加：<x-alert type="error" :errors="$errors" /> の1行
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. 修正後の完成形】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【tasks/create.blade.php 完成形】
 */

/**
 * @extends('layouts.app')
 *
 * @section('title', '新しいタスクの作成')
 *
 * @section('content')
 *     <h2>新しいタスクを作成</h2>
 *
 *     <form action="{{ route('tasks.store') }}" method="POST">
 *         @csrf
 *
 *         <x-alert type="error" :errors="$errors" />
 *
 *         <label for="title">タイトル:</label><br>
 *         <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="必須・255文字まで"><br>
 *         @error('title')
 *             <div class="text-danger">{{ $message }}</div>
 *         @enderror
 *         <br>
 *
 *         <label for="description">詳細:</label><br>
 *         <textarea name="description" id="description" rows="4">{{ old('description') }}</textarea><br>
 *         @error('description')
 *             <div class="text-danger">{{ $message }}</div>
 *         @enderror
 *         <br>
 *
 *         <button type="submit">保存</button>
 *     </form>
 * @endsection
 */

/**
 * 【tasks/edit.blade.php 完成形】
 *
 * @extends('layouts.app')
 *
 * @section('title', 'タスク編集')
 *
 * @section('content')
 *     <h2>タスク編集フォーム</h2>
 *     <p>ID: {{ $task->id }} のタスクを編集します（仮）</p>
 *
 *     <form method="POST" action="{{ route('tasks.update', ['task' => $task->id]) }}">
 *         @csrf
 *         @method('PUT')
 *
 *         <x-alert type="error" :errors="$errors" />
 *
 *         <div>
 *             <label>タイトル：
 *                 <input id="title" type="text" name="title" value="{{ old('title', $task->title) }}"
 *                     placeholder="必須・255文字まで">
 *                 @error('title')
 *                     <div class="text-danger">{{ $message }}</div>
 *                 @enderror
 *             </label>
 *         </div>
 *
 *         <div>
 *             <label>詳細：
 *                 <textarea id="description" name="description" rows="4">{{ old('description', $task->description) }}</textarea>
 *                 @error('description')
 *                     <div class="text-danger">{{ $message }}</div>
 *                 @enderror
 *             </label>
 *         </div>
 *
 *         <button type="submit">更新する</button>
 *     </form>
 *
 *     <p><a href="{{ route('tasks.index') }}">一覧へ戻る</a></p>
 *
 * @endsection
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. 動作確認の手順】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【手順1：Dockerを起動する】
 */

// ターミナルで以下を実行してください：
// cd ~/venpro/laravel-practice/src/laravel
// docker compose up -d

/**
 * 【手順2：ブラウザで確認する】
 *
 * 以下のURLを開いてください：
 * http://localhost/tasks/create
 * http://localhost/tasks/（一覧から任意のタスクの編集ボタンをクリック）
 *
 * 【確認ポイント：create ページ】
 * 1. タイトルを空にして「保存」ボタンを押す
 * 2. 赤色のエラーメッセージが表示されればOK
 *
 * 【確認ポイント：edit ページ】
 * 1. タイトルを空にして「更新する」ボタンを押す
 * 2. 赤色のエラーメッセージが表示されればOK
 *
 * 【よくあるエラーと対処法】
 *
 * ❌「Blade component [alert] not found.」と表示された場合
 * → resources/views/components/alert.blade.php が存在するか確認してください
 *
 * ❌ エラーメッセージが表示されない場合
 * → TaskControllerのバリデーション設定を確認してください
 *    （Step6・Step7でTaskControllerにvalidateを書いているか確認）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 💡 【@error との使い分け】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * エラー表示には2種類あります。今回の修正後も両方が残っています。
 *
 * 【1. x-alert → 全エラーをまとめて表示（フォームの上部）】
 *
 * <x-alert type="error" :errors="$errors" />
 *
 * → 「タイトルは必須です」「詳細は255文字以内です」などを
 *   まとめてリスト形式で表示します。
 *   フォームの一番上に置いて「何個エラーがあるか」を一覧で伝えます。
 *
 * 【2. @error → 各項目の横に個別表示（入力欄の近く）】
 *
 * @error('title')
 *     <div class="text-danger">{{ $message }}</div>
 * @enderror
 *
 * → 「タイトル」入力欄の直下に、その項目のエラーだけを表示します。
 *   「どの欄が間違っているか」を視覚的に伝えます。
 *
 * 【たとえ話：テスト答案の返却】
 * x-alert = 先生が「3問間違えました」と最初に言う（全体の報告）
 * @error = 各問題に赤ペンで「ここが違う」と書く（個別の指摘）
 *
 * 両方あることで「全体の把握」と「どこを直せばいいか」の両方がユーザーに伝わります。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ❓ 【Q&A：よくある疑問】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. expenses と tasks でエラー表示の書き方が違っていたのはなぜ？
 * A1. 作成した時期が違うからです。
 *
 *     tasks 系は Day7（認可・中間テーブル）のときに作りましたが、
 *     x-alert コンポーネントを作ったのは Day9（Step9-02）です。
 *
 *     後から「共通化した方がいい」と気づいて統一するのは、
 *     実際の開発でもよくある作業（リファクタリング）です。
 *
 * Q2. index.blade.php と show.blade.php は変更不要なのはなぜ？
 * A2. この2ファイルにはフォームもバリデーションエラー表示もないからです。
 *
 *     - index：タスク一覧を表示するだけ（入力欄なし）
 *     - show：タスク詳細を表示するだけ（入力欄なし）
 *
 *     バリデーションエラーはフォームを「送信した後」に出るものなので、
 *     表示専用のページには関係がありません。
 *
 * Q3. タスク削除のとき、エラーが出た場合はどうなる？
 * A3. 今の実装では削除にバリデーションを設けていないので、
 *     削除時のエラー表示は考慮しなくて大丈夫です。
 *
 * Q4. <x-alert type="error" :errors="$errors" /> の type を間違えたら？
 * A4. エラーメッセージが表示されません。
 *
 *     alert.blade.php の中では $type === 'error' という条件で
 *     エラー表示の処理が分岐しています。
 *     'error' 以外を書くと条件に合わないので何も表示されません。
 *
 *     正しい書き方：type="error"（エラー表示）
 *     正しい書き方：type="success"（成功メッセージ表示）
 *
 * Q5. 将来 GodeVen でフォームを作るとき、同じパターンが使える？
 * A5. そのまま使えます。
 *
 *     フォームがあるページには以下の2行を必ず入れる、
 *     というパターンを覚えておいてください：
 *
 *     1. フォームの上部に <x-alert type="error" :errors="$errors" />
 *     2. 各入力欄の下に @error('項目名') 〜 @enderror
 *
 *     この2行セットがGodeVenの全フォームで使える「エラー表示の定番パターン」です。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📖 【用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * x-alert（エックスハイフンアラート）
 * └─ Step9-02で作ったアラート（通知）コンポーネント
 *    成功メッセージ・エラーメッセージを統一した見た目で表示する部品
 *
 * :errors="$errors"（コロンエラーズ イコール エラーズ）
 * └─ バリデーションエラーの内容をコンポーネントに渡す書き方
 *    $errorsはLaravelが自動で用意する変数
 *
 * @error（アットエラー）
 * └─ 特定の入力項目に関するエラーだけを表示するBladeの命令
 *    @error('title') 〜 @enderror のように使う
 *
 * リファクタリング（Refactoring）
 * └─ 動作を変えずにコードの書き方を整理・改善すること
 *    今回の「古いエラー表示を x-alert に統一する」作業がまさにこれ
 *
 * バリデーション（Validation）
 * └─ 「入力値が正しいかチェックする」こと
 *    「必須」「文字数制限」「数値のみ」などのルールを設定する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📋 【第2部：学習中の追加Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q6. index.blade.php と show.blade.php の働きは何ですか？
 *     エラー表示がない理由は？
 *
 * A6. 働きはそれぞれ以下の通りです。
 *
 *     index.blade.php（インデックス）= 一覧表示ページ
 *     タスクの一覧を見せるだけのページです。
 *     ユーザーが何かを「入力・送信」する欄がないので、
 *     バリデーションエラーが発生しません。
 *
 *     show.blade.php（ショウ）= 詳細表示ページ
 *     1つのタスクの詳細（タイトル・説明など）を見せるだけのページです。
 *     こちらも表示専用なので、入力欄がありません。
 *
 *     【たとえ話：テストの採点】
 *     create・edit = テストの「解答用紙」→ 書き間違えたら赤ペンが入る（エラー表示が必要）
 *     index・show  = テストの「結果一覧」や「答案の閲覧」→ 見るだけなので赤ペンは入らない
 *
 * Q7. x-alert と @error の両方を使う方がUI・UXは良いですか？
 *
 * A7. はい、両方あった方がUX（ユーザー体験）は良いです。
 *
 *     x-alert（上部まとめ）= 「何が問題か」を一目で把握させる
 *     @error（個別表示）   = 「どこを直せばいいか」を入力欄の近くで伝える
 *
 *     項目が10個あって3項目でエラーが出た場合：
 *     x-alertだけ → 上部に3件のエラーが出るが、どの欄を直せばいいか探す必要がある
 *     @errorだけ  → 各欄の下にエラーは出るが、全体像はスクロールしないと分からない
 *     両方あると  → 上部で「3件ある」と把握 → 各欄の下で「ここを直す」とすぐ分かる
 *
 *     ただし現在の実装は同じエラーが2回表示されています（上部と入力欄下の両方）。
 *     GodeVenで本格的なフォームを作るときは
 *     「上部は全体件数だけ、個別欄に詳細を出す」という設計にするとよりスッキリします。
 *
 * Q8. なぜ入力欄の下にも表示されているのですか？ミスですか？
 *
 * A8. ミスではありません。意図的な設計です。
 *
 *     x-alert と @error は独立して動いています。
 *     x-alert  → $errors の中身を全部まとめて表示する
 *     @error   → $errors の中から title だけ取り出して表示する
 *
 *     同じ $errors を2つの方法で参照しているので、
 *     結果的に同じメッセージが2箇所に出ます。
 *     これはLaravelの標準的な書き方で、実際の現場でもよく使われるパターンです。
 *
 * Q9. x-alert と @error それぞれの処理の流れと関連ファイルを教えてください。
 *
 * A9. それぞれ以下の通りです。
 *
 *     【x-alert の場合】
 *     担当：全エラーをまとめてフォームの上部に表示する
 *     流れ：フォーム送信
 *           → TaskController がバリデーション実行
 *           → 失敗 → Laravelが $errors に全エラーを詰める
 *           → create.blade.php に戻る → <x-alert> が $errors を受け取る
 *           → components/alert.blade.php が開いて内容を表示する
 *     関連ファイル：
 *     ・app/Http/Controllers/TaskController.php（バリデーション実行）
 *     ・resources/views/components/alert.blade.php（表示担当の部品）
 *     ・resources/views/tasks/create.blade.php（呼び出し元）
 *
 *     【@error の場合】
 *     担当：title という1つの項目のエラーだけを、入力欄の真下に表示する
 *     流れ：$errors の中から title に関するエラーだけ取り出す
 *           → あれば $message に入れて <div> で表示する
 *           → なければ何も表示しない
 *     関連ファイル：
 *     ・resources/views/tasks/create.blade.php（ここだけで完結。外部ファイルを呼ばない）
 *
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🏁 【Day9 完了まとめ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step9-04で tasks 系のエラー表示を x-alert に統一しました。
 * これでアプリ全体（expenses・tasks 両方）のエラー表示が統一されました。
 *
 * 【Day9で学んだこと】
 * - Step9-01：layouts/app.blade.php でページ全体の枠を共通化（@extends / @section / @yield）
 * - Step9-02：components/alert.blade.php などの部品を作成（x-コンポーネント）
 * - Step9-03：expenses 系を x-alert・レイアウトに統合（リファクタリング）
 * - Step9-04：tasks 系のエラー表示を x-alert に統一（リファクタリング）
 *
 * 【GodeVenへの応用】
 * - 全ページで @extends('layouts.app') を使う
 * - フォームのあるページには x-alert + @error の2行セットを入れる
 * - 共通部品はコンポーネントとして切り出す
 */
