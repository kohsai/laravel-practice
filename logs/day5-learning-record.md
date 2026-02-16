# Day5学習記録

## 学習期間

2025/11/14 〜 2026/02/15

## 学習範囲

Step5-01〜Step5-06（Eloquent基礎とリレーション）

---

## 主な学習内容

### Step5-01: tinkerの使い方
- Laravel tinkerの起動方法と基本操作
- 対話的にPHPコードを実行する環境
- usersテーブルの確認

### Step5-02: Eloquent基本操作（データ取得・検索）
- all()：全データ取得
- find()：ID指定で取得
- where()：条件指定で検索
- first()：最初の1件を取得
- get()：クエリビルダからデータ取得
- Collection（コレクション）の概念

**教材ファイル：** `src/day5/day5_step5-02_eloquent_read.php`（統合版、1,088行）

**質問11問：**
1. all()の読み方と意味
2. User::all()の::の意味
3. where()の第3引数が省略できる理由
4. ::（コロンコロン）の正式名称
5. ::とクラスメソッドの関係
6. クラスの概念
7. アロー（->）の使い方
8. firstOrFail()の意味
9. ->とアロー演算子の正式名称
10. インスタンスメソッドとは
11. ->nameの意味

### Step5-03: Eloquent基本操作（作成・更新・削除）
- create()：新規データ作成
- update()：データ更新
- delete()：データ削除
- Mass Assignment（一括代入）
- $fillableプロパティの役割
- tinkerでのCUD操作

**教材ファイル：** `src/day5/day5_step5-03_eloquent_cud.php`（統合版、1,129行）

**質問9問：**
1. Mass Assignmentの読み方
2. fillableの読み方と意味
3. save()とupdate()の違い
4. delete()とdestroy()の違い
5. 論理削除（Soft Delete）とは
6. 主キーとは
7. $userとUserの違い
8. ->save()の使い方
9. 変数とインスタンスの違い

### Step5-04: Expenseモデルとマイグレーション作成
- php artisan make:modelコマンド
- マイグレーションファイルの構造
- カラム定義とデータ型
- 外部キー制約の設定
- Expenseモデルの作成

**作成ファイル：**
- `app/Models/Expense.php`
- `database/migrations/2026_01_17_184249_create_expenses_table.php`

**教材ファイル：** `src/day5/day5_step5-04_expense_model.php`（統合版）

**質問7問：**
1. マイグレーションファイルの日付の意味
2. foreignIdの意味
3. constrainedの意味
4. onDeleteの意味
5. cascadeの意味
6. $fillableの設定理由
7. 外部キーの仕組み

### Step5-05: リレーション基礎（概念とhasMany/belongsTo）
- リレーション（関係）の概念
- 1対多の関係（one-to-many）
- hasMany（ハズメニー）：「1人は複数の〇〇を持つ」
- belongsTo（ビロングストゥー）：「1つの〇〇は1人に属する」
- 外部キー（user_id）の活用

**教材ファイル：** `src/day5/day5_step5-05_relations.php`（統合版、809行）

**質問8問：**
1. public functionの「公開された」の意味
2. functionの意味
3. $thisについて
4. = と -> の使い分け
5. 「子」の考え方と対応表
6. 外部キーの仕組み
7. クエリビルダとは
8. get()を付け忘れるとどうなるか

### Step5-06: リレーションの実践（実際に定義して動作確認）
- Userモデルにexpenses()メソッド追加（hasMany）
- Expenseモデルにuser()メソッド追加（belongsTo）
- tinkerでリレーション動作確認
- 親から子を取得：User::find(1)->expenses
- 子から親を取得：Expense::find(2)->user
- 条件付き取得：User::find(1)->expenses()->where()->get()
- 件数取得：User::find(1)->expenses()->count()

**編集ファイル：**
- `src/laravel/app/Models/User.php`
- `src/laravel/app/Models/Expense.php`

**教材ファイル：** `src/day5/day5_step5-06_relations_practice.php`（統合版）

**質問2問：**
1. SQLとは何か？自動的に組み立てられるメリットは？Laravelを使わないとどうなるか？
2. Laravelを使わない人はいるか？どんな場合か？

---

## 主なエラーと解決方法

### エラー1: Day切り替え時の事前準備不足

**発生時期：** Day5開始時

**原因：**
- Day切り替え前の準備フロー（事前確認・整備フェーズ）が確立されていなかった
- いきなりコマンド実行を勧められた

**解決：**
- 「Day切り替え時の準備ルール（v1.0）」を制定
- 各Day開始前に構成案を提示してから着手するフローを確立

### エラー2: 状態連携の不足

**発生時期：** Day5開始時

**原因：**
- 学習履歴（何を学んだか）と実行状態（何が存在するか）が接続されていなかった
- usersテーブルが存在するのに「存在しない可能性」と誤認

**解決：**
- 「状態連携ルール（v1.0）」を制定
- 学習記録と現在の構成状態を常に接続して把握

### エラー3: 教材ファイルのPHPエラー表示

**発生時期：** Step5-06

**原因：**
- 教材ファイル内に説明用のコメントと実際のコード例が混在
- VSCodeのPHP Linterが「これはPHPとして正しくない」と判断

**解決：**
- KOHさんが一部をコメントアウトして解決
- 今後、教材でエラーが出る場合は記述方法を再検討する方針

---

## 作成・編集ファイル

### 作成したファイル

**モデル：**
- `src/laravel/app/Models/Expense.php`

**マイグレーション：**
- `src/laravel/database/migrations/2026_01_17_184249_create_expenses_table.php`

**教材ファイル（6件）：**
- `src/day5/day5_step5-01_tinker.php`
- `src/day5/day5_step5-02_eloquent_read.php`（統合版、1,088行）
- `src/day5/day5_step5-03_eloquent_cud.php`（統合版、1,129行）
- `src/day5/day5_step5-04_expense_model.php`（統合版）
- `src/day5/day5_step5-05_relations.php`（統合版、809行）
- `src/day5/day5_step5-06_relations_practice.php`（統合版）

### 編集したファイル

**モデル：**
- `src/laravel/app/Models/User.php`（expenses()メソッド追加）
- `src/laravel/app/Models/Expense.php`（user()メソッド追加）

---

## データベース状態

### 作成したテーブル
- `expenses`テーブル（8カラム）
  - id, user_id, category, amount, description, spent_at, created_at, updated_at
  - 外部キー制約：user_id → users.id (cascade削除)

### テストデータ
- User（id=1）：テストユーザー
- Expense（id=2）：コンビニでお昼ご飯（500円）

---

## Git管理

### ブランチ
- `day5-eloquent`ブランチで作業
- 最終的にmainブランチへマージ（Day5完了後）

### 主なコミット

1. `a88e21e` - Step5-02完了: Eloquent基本操作（読み取り）統合版
2. `421f4bb` - Step5-03完了: Eloquent基本操作（作成・更新・削除）統合版
3. `b3807f8` - Step5-04完了: Expenseモデルとマイグレーション作成（統合版）
4. `a517952` - Step5-05完了: リレーション基礎（統合版）
5. `80127be` - Step5-06完了: リレーションの実践（hasMany/belongsTo定義と動作確認）
6. `6227927` - Step5-06完了: リレーション実践（統合版 - SQL・技術選択のQ&A追加）

---

## 学んだ重要な概念

### Eloquent ORM
- LaravelのORM（Object-Relational Mapping）
- SQLを書かずにPHPでデータベース操作
- 直感的で安全なコード

### Collection
- 複数のデータが入った「箱」
- 配列の進化版
- 便利なメソッドが多数用意されている

### Mass Assignment
- 複数のカラムを一度に代入する仕組み
- $fillableで許可するカラムを指定
- セキュリティ対策として重要

### リレーション
- テーブル間の関係を定義
- hasMany：1対多の「親」側
- belongsTo：1対多の「子」側
- 外部キーで関連付け

---

## 次への課題

### Day6の学習内容
- バリデーション（入力検証）
- FormRequest（検証ロジックの再利用）
- エラーメッセージのカスタマイズ

### Day6開始前の準備
- roadmap更新（Day6-10の章分けを事前に決定）
- 各Dayの学習目標・Step構成を明確化

### 技術的な理解の深化
- tinkerや教材の内容でまだ完全に理解できていない部分がある
- 今後の実践（Laravelコーヒー、GodeVen開発）で復習・定着を図る

### 教材作成の改善
- 教材ファイルでPHPエラーが出ないよう記述方法を工夫
- 必要に応じてteaching_style_guide.mdを更新

---

## 所感

### 学習の進め方について
- 質問→統合版の流れが定着し、学習記録が充実した
- teaching_style_guide.mdに従った平易な言葉での説明が理解を助けた
- 実行可能コードの記述ルールが明確化され、コピペで動作確認できるようになった

### Day5の学習量
- 6ステップ、合計37問の質問
- 教材ファイルの総行数：約4,000行超
- 学習期間：約3ヶ月（途中、他の学習・作業を含む）

### 運用ルールの確立
- Day切り替え時の準備ルール
- 状態連携ルール
- 教材の運用フロー（v1.1）
- これらのルールにより、今後の学習がスムーズになることが期待される

---

**作成日：** 2026/02/15  
**作成者：** Kloge  
**対象：** Laravel-Practice-Support プロジェクト Day5