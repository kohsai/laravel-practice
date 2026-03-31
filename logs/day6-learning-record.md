# Day6学習記録

## 学習期間

2026/03/03 〜 2026/03/31

## 学習範囲

Step6-01〜Step6-06（バリデーションとFormRequest応用）

---

## 主な学習内容

### Step6-01：バリデーションの基礎概念と必要性
- バリデーション（入力チェック）とは何か、なぜ必要かを理解した
- フォームから送られるデータは信用できないという考え方を学んだ
- バリデーションがない場合のリスク（不正データ・セキュリティ問題）を理解した

**教材ファイル：** `src/day6/day6_step6-01_validation_basics.php`

### Step6-02：コントローラーでのバリデーション実装
- `$request->validate()` を使ったバリデーションの書き方を習得した
- `ExpenseController` を `php artisan make:controller --resource` で作成した
- `Route::resource` で7つのCRUDルートを一行で設定する方法を学んだ
- `php artisan route:list` でルート一覧を確認する方法を習得した

**作成ファイル：**
- `src/laravel/app/Http/Controllers/ExpenseController.php`
- `src/laravel/routes/web.php`（Route::resource追加）

**教材ファイル：** `src/day6/day6_step6-02_expense_controller.php`

### Step6-03：Bladeでのエラーメッセージ表示
- `$errors` 変数（Laravelが自動で用意するエラーの入れ物）の使い方を学んだ
- `@error` ディレクティブで特定フィールドのエラーだけ表示する方法を習得した
- `old()` 関数でバリデーション失敗時に入力値を保持する仕組みを理解した
- `create.blade.php` を作成し、実際のフォームにエラー表示を実装した

**作成ファイル：**
- `src/laravel/resources/views/expenses/create.blade.php`

**教材ファイル：** `src/day6/day6_step6-03_blade_errors.php`

### Step6-04：主要バリデーションルールの種類
- `email`・`unique`・`confirmed`・`date`・`in`・`nullable` などの主要ルールを学んだ
- `nullable`（空欄を許可）と `sometimes`（入力時のみチェック）の使い分けを理解した
- `unique` ルールで更新時に自分自身を除外する `Rule::unique()->ignore()` を学んだ
- `before_or_equal:today` で今日以前の日付のみ許可する書き方を習得した

**教材ファイル：** `src/day6/day6_step6-04_validation_rules.php`（統合版）

**質問5問：**
1. `nullable` の読み方と意味
2. `unique` ルールで更新時に自分自身を除外する方法
3. `in` ルールで半角カンマが必要な理由
4. `regex` と `sometimes` が今回の学習範囲外である理由
5. `before_or_equal` の意味と使い方

### Step6-05：FormRequestの作成と基本
- FormRequest（バリデーション専用クラス）の概念と役割を理解した
- `php artisan make:request StoreUserRequest` でFormRequestを生成した
- `rules()`・`authorize()`・`messages()` の3つのメソッドの役割を習得した
- コントローラーの引数の型を変えるだけでFormRequestが自動実行される仕組みを理解した
- `messages()` で個別のカスタムエラーメッセージを日本語で設定した

**作成ファイル：**
- `src/laravel/app/Http/Requests/StoreUserRequest.php`
- `src/laravel/app/Http/Controllers/ExpenseController.php`（FormRequest適用）

**教材ファイル：** `src/day6/day6_step6-05_form_request.php`

### Step6-06：カスタムエラーメッセージと日本語化
- Laravelの言語ファイル（lang/）の仕組みと役割を理解した
- `laravel-lang/lang` パッケージをComposerでインストールした（v14.8.0）
- `php artisan lang:add ja` で日本語言語ファイルを `resources/lang/ja/` に追加した
- `resources/lang/ja/validation.php` の `attributes` にフィールド名の日本語訳を追記した
- `messages()` と言語ファイルの優先順位・使い分けを理解した
- Laravel 10とLaravel 9以前の言語ファイルの場所の違いを学んだ

**作成・編集ファイル：**
- `src/laravel/resources/lang/ja/validation.php`（attributes追記）
- `src/laravel/resources/lang/ja/auth.php`（新規追加）
- `src/laravel/resources/lang/ja/pagination.php`（新規追加）
- `src/laravel/resources/lang/ja/passwords.php`（新規追加）
- `src/laravel/resources/lang/ja.json`（新規追加）
- `src/laravel/config/app.php`（locale = 'ja' 確認済み）
- `src/laravel/composer.json` / `composer.lock`（laravel-lang/lang追加）

**教材ファイル：** `src/day6/day6_step6-06_validation_messages.php`（統合版）

**質問10問：**
1. Laravelのバージョン確認方法（`php artisan --version`）
2. `lang/ja/` と `resources/lang/ja/` どちらが正しいか
3. `php artisan lang:publish` と `lang:add ja` の違い
4. PHPバージョン（8.1/8.2）はどこで決まるか（Dockerfile）
5. `laravel-lang/lang` v14とv15の違い
6. Laravel 9以前と10以降の言語ファイルの場所の違い
7. `locale` と `fallback_locale` の意味
8. `messages()` と言語ファイルの優先順位
9. `attributes` と `attributes()` の使い分け
10. `validation.required` がそのまま表示される原因と対処法

---

## 主なエラーと解決方法

### エラー1：`validation.required` という文字がそのまま表示された

**発生時期：** Step6-06

**原因：**
- Klogeの誤指示で `resources/lang/ja/` フォルダを削除したため言語ファイルが読み込めなくなった
- Laravel 10は `resources/lang/` を優先して読むが、その理解が不足していた

**解決：**
- `lang/ja/` のファイルを `resources/lang/ja/` にコピーして戻した
- `php artisan config:clear` でキャッシュをクリアした

**教訓：**
- Laravel 10は `resources/lang/` が優先される
- ファイルを削除する前に必ず `ls` で確認する
- コマンド実行後は必ず動作確認する

### エラー2：日本語ファイルの作成先が予想と異なった

**発生時期：** Step6-06

**原因：**
- `php artisan lang:add ja` が `resources/lang/ja/` に作成したが、`lang/ja/` に作られると思い込んでいた（Klogeの誤判断）

**解決：**
- `ls` コマンドで実際のファイル場所を確認してから編集する手順を確立した

**教訓：**
- コマンド実行後は必ず `ls` で実際の作成先を確認してから編集する

### エラー3：Dockerコンテナ内外でコマンドが混在して実行された

**発生時期：** Step6-06

**原因：**
- 複数のコマンドをまとめて貼り付けたため、コンテナの内と外でコマンドが混在した

**解決：**
- `Ctrl+C` でキャンセルし、コンテナに入り直して1行ずつ実行した

**教訓：**
- Dockerコンテナに関係するコマンドは必ず1行ずつ実行する
- `I have no name!@...` がコンテナ内、`kohsai@kohsaipc` がコンテナ外の目印

---

## 作成・編集ファイル

### 教材ファイル（6件）
- `src/day6/day6_step6-01_validation_basics.php`
- `src/day6/day6_step6-02_expense_controller.php`
- `src/day6/day6_step6-03_blade_errors.php`
- `src/day6/day6_step6-04_validation_rules.php`（統合版）
- `src/day6/day6_step6-05_form_request.php`
- `src/day6/day6_step6-06_validation_messages.php`（統合版）

### Laravelファイル（新規作成）
- `src/laravel/app/Http/Controllers/ExpenseController.php`
- `src/laravel/app/Http/Requests/StoreUserRequest.php`
- `src/laravel/resources/views/expenses/create.blade.php`
- `src/laravel/resources/lang/ja/auth.php`
- `src/laravel/resources/lang/ja/pagination.php`
- `src/laravel/resources/lang/ja/passwords.php`
- `src/laravel/resources/lang/ja/validation.php`（attributes追記）
- `src/laravel/resources/lang/ja.json`

### Laravelファイル（編集）
- `src/laravel/routes/web.php`（Route::resource追加）
- `src/laravel/composer.json` / `composer.lock`（laravel-lang/lang追加）

---

## 環境の状態

### バージョン
- Laravel 10.48.29
- PHP 8.1（`docker/php/Dockerfile` の `FROM php:8.1-fpm` で指定）

### 言語ファイルの状態
- `resources/lang/ja/` に日本語ファイルが配置済み
- `config/app.php` の `locale` = `'ja'`、`fallback_locale` = `'ja'`

---

## Git管理

### ブランチ
- `day6-validation` ブランチで作業
- mainブランチへのマージ：Day6完了後に実施予定

### 主なコミット
1. `day6_step6-02_expense_controller.php完了`
2. `day6_step6-03_blade_errors.php完了`
3. `day6_step6-04_validation_rules.php統合版`
4. `day6_step6-05_form_request.php`
5. `day6_step6-06_validation_messages.php`（統合版）

---

## 学んだ重要な概念

### バリデーション
- ユーザーの入力を信用せず、必ずサーバー側でチェックする
- `$request->validate()` でシンプルに実装できる
- バリデーション失敗時はLaravelが自動でリダイレクトし、`$errors` にメッセージを格納する

### FormRequest
- バリデーションをコントローラーから切り出して専用クラスに書く仕組み
- コントローラーの引数の型を変えるだけで自動実行される
- `rules()` でルール、`messages()` で個別メッセージ、`attributes()` でフィールド名を定義

### 言語ファイル
- Laravelのエラーメッセージをまとめて管理する「辞書」
- Laravel 10以降は `lang/` が正式な場所だが、後方互換性で `resources/lang/` も有効（こちらが優先）
- `attributes` にフィールド名の日本語訳を書くことでメッセージ内のフィールド名が日本語になる
- `messages()` の個別設定が言語ファイルより優先される

---

## 次への課題

### Day7の学習内容
- 認可（Gate / Policy）
- 中間テーブル（多対多リレーション）

### Day6完了後の作業
- `day6-validation` ブランチを `main` にマージする
- Laravelコーヒーでフェーズ2（ヒントなし）への移行を検討する（連続5問80%以上達成済み）

### 技術的な理解の深化
- `messages()` と言語ファイルの使い分けを実践で定着させる
- GodeVen開発時にPHP 8.2以上への変更が必要（Dockerfileの修正）

---

**作成日：** 2026/03/31
**作成者：** Kloge
**対象：** Laravel-Practice-Support プロジェクト Day6