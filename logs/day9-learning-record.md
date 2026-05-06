# Day9学習記録

## 学習期間

2026/05/02 〜 2026/05/06

## 学習範囲

Step9-01〜Step9-04（Bladeレイアウト・コンポーネント・既存ビューの統合）

---

## 主な学習内容

### Step9-01：Bladeレイアウトの仕組みを理解する

- `@extends`（アットエクステンズ）= 「この設計図を使う」と宣言する命令
- `@section`（アットセクション）= 「ここが○○セクションの内容です」と宣言する命令
- `@yield`（アットイールド）= 「ここに○○セクションの内容を差し込む」場所を指定する命令
- `resources/views/layouts/app.blade.php` を作成し、HTML骨格・ヘッダー・フッターを共通化
- tasks系（index）はすでに `@extends` 形式になっていることを確認
- `<title>@yield('title', 'Laravel App')</title>` の1行記述 vs 3行記述（動作は同じ、好みの問題）

**作成ファイル：**
- `resources/views/layouts/app.blade.php`

**教材ファイル：** `src/day9/day9_step9-01_blade_layout.php`

**質問1問：**
1. `<title>` タグの記述を1行から3行に変えたが問題ないか？（どちらでも動作は同じ、現場では1行が多い）

---

### Step9-02：Bladeコンポーネントを作る

- コンポーネント（Component）= ページの中で繰り返し使う「部品」
- `resources/views/components/` フォルダに保存し、`<x-ファイル名 />` で呼び出す
- `@props`（アットプロップス）= コンポーネントが受け取る値を定義する宣言（デフォルト値も設定可）
- `$slot`（スロット）= コンポーネントの中の「何でも入れていい空き場所」
- `:`（コロン付き属性）= 属性値をPHPとして実行する記法
- `docker compose exec php php artisan make:component Alert --view` で `alert.blade.php` を作成
- `--view` オプション = Bladeファイルのみ生成（PHPクラスなし）
- 作成したコンポーネント：`alert`（成功・エラーメッセージ）・`button`・`card`

**作成ファイル：**
- `resources/views/components/alert.blade.php`
- `resources/views/components/button.blade.php`
- `resources/views/components/card.blade.php`

**教材ファイル：** `src/day9/day9_step9-02_blade_components.php`（統合版）

**質問複数問：**
1. layoutsフォルダとcomponentsフォルダの関係は？（間取りと家具の関係、どちらが上位でもない）
2. `@props` がないコンポーネントでも動くか？（動く。ただし書く方がデフォルト値を設定できて便利）
3. `$slot` は必ず使わないといけないか？（alertのように値だけ受け取ればいい場合は不要）
4. `make:component` に `--view` を付けないと何が作られるか？（BladeファイルとPHPクラスの2つ）

---

### Step9-03：expenses系をレイアウトに統合する

- expenses系3ファイル（index・create・edit）は `<!DOCTYPE html>` から書く古い形式だった
- 3ファイルを `@extends('layouts.app')` 形式に移行
- エラー表示を `<x-alert type="error" :errors="$errors" />` に統一
- 成功メッセージを `<x-alert type="success" :message="session('success')" />` に統一
- `x-card` コンポーネントでテーブルを囲む
- 「バリデーション処理はどこに書くか」の確認：コントローラー（変わらない）、表示だけコンポーネントに移した

**編集ファイル：**
- `resources/views/expenses/index.blade.php`
- `resources/views/expenses/create.blade.php`
- `resources/views/expenses/edit.blade.php`

**教材ファイル：** `src/day9/day9_step9-03_layout_integration.php`（統合版）

**質問3問：**
1. A案（`@extends`のまま）とB案（`@extends`に変更）のどちらを選ぶか？（B案を採用、共通化のため）
2. バリデーション処理はどこに書くか？（コントローラー。表示だけをコンポーネントに移した）
3. Step9-03の全体像をまとめると？（各ページの固有コンテンツ以外をすべて共通化した）

---

### Step9-04：tasks系のエラー表示をx-alertに統一する（追加Step）

- tasks系4ファイルはすでに `@extends('layouts.app')` 形式になっていた
- create・editの2ファイルに古いエラー表示（`@if ($errors->any()) 〜 @endif`）が残っていた
- この8行を `<x-alert type="error" :errors="$errors" />` の1行に置き換え
- index・showはエラー表示なし（表示専用ページのため）
- リファクタリング（Refactoring）= 動作を変えずにコードの書き方を整理・改善すること

**編集ファイル：**
- `resources/views/tasks/create.blade.php`
- `resources/views/tasks/edit.blade.php`

**教材ファイル：** `src/day9/day9_step9-04_tasks_alert.php`（統合版）

**質問複数問：**
1. index・showの働きとエラー表示がない理由は？（表示専用ページで入力欄がないため）
2. `:errors="$errors"` の処理の流れは？（フォーム送信→バリデーション→$errorsに書き込み→コンポーネントに手渡し→表示）
3. x-alertと@errorの両方使う方がUI・UXは良いか？（良い。上部まとめ＋個別表示の2段構え）
4. 同じエラーが2箇所に出るのはミスか？（ミスではない。$errorsを2つの方法で参照しているため）
5. x-alertと@errorそれぞれの処理の流れと関連ファイルは？（x-alertはコントローラー→コンポーネント経由、@errorはbladeファイル内で完結）

---

## 主なエラーと解決方法

### エラー1：Step9-02でプッシュされなかった問題

**発生時期：** Step9-02

**原因：**
コミット後に統合版に差し替えたため、Gitが「コミット済みの内容と違う」と検知して `modified` 状態になった。

**解決：**
再度 `git add .` → `git commit` → `git push` を実行。

**教訓：**
統合版への差し替えはコミット前に行う。コミット後に差し替えた場合は必ず再コミットが必要。

---

## 作成・編集ファイル

### 教材ファイル（4件）
- `src/day9/day9_step9-01_blade_layout.php`
- `src/day9/day9_step9-02_blade_components.php`（統合版）
- `src/day9/day9_step9-03_layout_integration.php`（統合版）
- `src/day9/day9_step9-04_tasks_alert.php`（統合版）

### Laravelファイル（新規作成）
- `resources/views/layouts/app.blade.php`
- `resources/views/components/alert.blade.php`
- `resources/views/components/button.blade.php`
- `resources/views/components/card.blade.php`

### Laravelファイル（編集）
- `resources/views/expenses/index.blade.php`（@extends移行・x-alert・x-card適用）
- `resources/views/expenses/create.blade.php`（@extends移行・x-alert適用）
- `resources/views/expenses/edit.blade.php`（@extends移行・x-alert適用）
- `resources/views/tasks/create.blade.php`（x-alert適用）
- `resources/views/tasks/edit.blade.php`（x-alert適用）

---

## Git管理

### ブランチ
- `day9-blade-components` ブランチで作業
- mainブランチへのマージ：未実施（次のステップで実施）

### 主なコミット
1. `day9_step9-01_blade_layout.php`
2. `day9_step9-02_blade_components.php`
3. `day9_step9-03_layout_integration.php`
4. `day9_step9-04_tasks_alert.php`

---

## 学んだ重要な概念

### Bladeレイアウト（layouts/）
ページ全体の共通枠。`@extends` / `@section` / `@yield` の3つで構成される。
「間取り」のたとえ：全ページ共通のHTML骨格・ヘッダー・フッターをここに書く。

### Bladeコンポーネント（components/）
ページの中で繰り返し使う部品。`<x-コンポーネント名 />` で呼び出す。
「家具」のたとえ：アラート・ボタン・カードなどを部品として切り出す。

### リファクタリング
動作を変えずにコードの書き方を整理・改善すること。
今回のStep9-03・04は典型的なリファクタリング作業（古い書き方を共通コンポーネントに統一）。

### GodeVenへの応用
- 全ページで `@extends('layouts.app')` を使う
- フォームのあるページには `<x-alert type="error" :errors="$errors" />` + `@error` の2行セット
- 共通部品はコンポーネントとして `components/` に切り出す

---

## 次への課題

### Day10の学習内容
テスト・本番環境準備（Laravel-practiceの最終Day）

### 技術的な理解の深化
- `$slot` を活用したより複雑なコンポーネント設計
- コンポーネントにPHPクラス（Alert.php）を持たせる設計（GodeVen開発フェーズで必要になれば）
- エラー表示の改善（上部は件数のみ、詳細は各欄の下という設計）