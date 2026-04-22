# Day7学習記録

## 学習期間

2026/03/31 〜 2026/04/22

## 学習範囲

Step7-01〜Step7-05（認可・中間テーブル）

---

## 主な学習内容

### Step7-01：認証と認可の違い・Gateの基礎

- 認証（Authentication）と認可（Authorization）の違いを整理した
- Gate::define() で認可ルールを定義する方法を学んだ
- $this->authorize() でControllerに認可チェックを実装した
- @can / @cannot でBladeテンプレートの表示制御を行った
- is_admin カラムを使った管理者判定を実装した

**教材ファイル：** `src/day7/day7_step7-01_gate_basics.php`（統合版）

**学習中の気づき：**
- @cannot だけではログインしていないユーザーにも表示されてしまう
- @auth と組み合わせることで「ログインしていて、かつ管理者でない人」に絞れる
- tinker でカスタムモデルを使う際は use 文か完全修飾名が必要（今回は User は自動エイリアスされた）

---

### Step7-02：Policyの作成と使い方

- Gate（シンプルな認可）と Policy（モデルに紐づいた認可）の使い分けを学んだ
- `php artisan make:policy` でPolicyファイルを生成した
- ExpensePolicy に viewAny・view・create・update・delete を実装した
- 「自分の Expense だけ編集・削除できる」というルールを実装した
- Controller の $this->authorize() と Blade の @can でPolicyを利用した

**教材ファイル：** `src/day7/day7_step7-02_policy.php`（統合版）

---

### Step7-03：多対多リレーションと中間テーブル

- 1対多（ユーザー→支出）と多対多（ユーザー↔タグ）の違いを整理した
- 中間テーブルが必要な理由を「部活の名簿」のたとえ話で理解した
- tags テーブルと tag_user 中間テーブルをマイグレーションで作成した
- belongsToMany() で多対多リレーションを定義した
- attach()・detach()・sync() の違いと使い分けを学んだ
- tinker で実際に操作して動作確認した

**教材ファイル：** `src/day7/day7_step7-03_many_to_many.php`（統合版）

**学習中の気づき：**
- 中間テーブルの命名規則：2つのモデル名をアルファベット順・単数形でつなぐ（tag_user）
- 「たたいた」の読み方について確認（「たいたい」は誤り）
- attach() を2回実行すると重複が発生する → syncWithoutDetaching() で防止できる

---

### Step7-04：タグ機能の実装（Klogeが無断追加したStep）

- Step7-03で学んだ多対多を実際のアプリに実装した
- TagSeeder でタグのサンプルデータ（10件）を作成した
- ExpenseController を修正してタグの取得・保存（sync）を実装した
- create.blade.php にチェックボックスでタグ選択UIを追加した
- index.blade.php・edit.blade.php を新規作成した
- ブラウザでタグの付与・編集が動作することを確認した

**教材ファイル：** `src/day7/day7_step7-04_tag_implementation.php`（統合版）

**学習中の気づき：**
- コピペ作業になってしまい「なぜそう書くか」を考えずに進めてしまった
- この反省が Step7-05 の教材方針（概念厚め・コード最小限）につながった
- tinker で `Class "Tag" not found.` → `use App\Models\Tag;` で解決

---

### Step7-05：認可と多対多の組み合わせ（ロールによる権限制御）

- is_admin カラムによる2択の権限管理と、ロールシステムの違いを理解した
- Gate・Policy・ロールの3つが連携して動く仕組みを整理した
- roles テーブルと role_user 中間テーブルをマイグレーションで作成した
- Role モデルに users() リレーションを定義した
- User モデルに roles() と hasRole() を追加した
- tinker でロールの作成・付与・hasRole() の動作確認を行った
- ExpensePolicy の update() と delete() に「admin なら無条件許可」を追加した
- ExpenseController の index() をロール判定に基づいて修正した
- ブラウザで2アカウントの表示の違いと403エラーを確認した

**教材ファイル：** `src/day7/day7_step7-05_role_authorization.php`（統合版）

**学習中の気づき：**
- tinker でのカスタムモデル名前空間エラー（Step7-04と同じ原因・同じ解決方法）
- タイポ：`Expenses::with('tag')` → 正しくは `Expense::with('tags')`（モデルは単数形、引数はメソッド名と一致）
- コピペ作業への反省：Laravelコーヒーと練習問題で定着させる方針を決定

---

## 主なエラーと解決方法

### エラー1：Class "Role" not found.

**発生時期：** Step7-05 tinker 作業中

**原因：**
tinker 内でカスタムモデルを使う場合、`use` 文か完全修飾名が必要。Step7-04 でも同じエラー（Class "Tag" not found.）が発生していた。

**解決：**
```
use App\Models\Role;
```
を先に宣言してから実行する。

**教訓：**
tinker でカスタムモデルを使う時は必ず use 文を先に宣言する習慣をつける。

---

### エラー2：`make:migrate` コマンドエラー

**発生時期：** Step7-05 マイグレーション実行時

**原因：**
`php artisan make:migrate` と誤入力。正しくは `php artisan migrate`（make なし）。

**解決：**
正しいコマンドを入力して実行。

**教訓：**
`make:model`・`make:migration`・`migrate` の区別を意識する。

---

### エラー3：タイポ（Expenses::with('tag')）

**発生時期：** Step7-05 ExpenseController 修正時

**原因：**
① モデル名を複数形にしてしまった（Expenses → Expense）
② リレーション名を単数形にしてしまった（'tag' → 'tags'）

**解決：**
`Expense::with('tags')` に修正。

**教訓：**
- Laravel のモデル名は常に単数形
- with() の引数はモデルで定義したメソッド名と一致させる

---

## 作成・編集ファイル

### 教材ファイル（5件）
- `src/day7/day7_step7-01_gate_basics.php`（統合版）
- `src/day7/day7_step7-02_policy.php`（統合版）
- `src/day7/day7_step7-03_many_to_many.php`（統合版）
- `src/day7/day7_step7-04_tag_implementation.php`（統合版）
- `src/day7/day7_step7-05_role_authorization.php`（統合版）

### Laravelファイル（新規作成）
- `src/laravel/app/Models/Tag.php`
- `src/laravel/app/Models/Role.php`
- `src/laravel/app/Policies/ExpensePolicy.php`
- `src/laravel/database/migrations/xxxx_create_tags_table.php`
- `src/laravel/database/migrations/xxxx_create_tag_user_table.php`
- `src/laravel/database/migrations/xxxx_create_roles_table.php`
- `src/laravel/database/migrations/xxxx_create_role_user_table.php`
- `src/laravel/database/seeders/TagSeeder.php`
- `src/laravel/resources/views/expenses/index.blade.php`
- `src/laravel/resources/views/expenses/edit.blade.php`

### Laravelファイル（編集）
- `src/laravel/app/Models/User.php`（roles()・hasRole() 追加）
- `src/laravel/app/Models/Expense.php`（tags() 追加）
- `src/laravel/app/Http/Controllers/ExpenseController.php`（タグ対応・ロール判定追加）
- `src/laravel/resources/views/expenses/create.blade.php`（タグチェックボックス追加）
- `src/laravel/app/Providers/AppServiceProvider.php`（Gate 定義追加）

---

## 環境の状態

### DBの状態（Day7完了時点）
- tags テーブル：10件（食費・娯楽・節約・外食・日用品・交通費・趣味・医療費・水道光熱費・その他）
- roles テーブル：2件（admin・editor）
- role_user テーブル：1件（id=1 のユーザーに admin ロールを付与）
- expenses テーブル：3件（user_id=1 が2件、user_id=2 が1件）

---

## Git管理

### ブランチ
- `day7-authorization` ブランチで作業
- main ブランチへのマージ：未実施（次の手順で実施）

### 主なコミット
1. `day7_step7-01_gate_basics.php`
2. `day7_step7-02_policy.php`
3. `day7_step7-03_many_to_many.php`
4. `day7_step7-04_tag_implementation.php`
5. `day7_step7-05_role_authorization.php`（初回）
6. `day7_step7-05_role_authorization.php`（統合版）

---

## 学んだ重要な概念

### 認証と認可の違い
- 認証（Authentication）= 「あなたは誰ですか？」→ ログイン・ログアウト
- 認可（Authorization）= 「あなたは何をしていいですか？」→ Gate・Policy

### Gate・Policy・ロールの連携
- Gate：シンプルな yes/no の判断（入口の警備員）
- Policy：特定モデルに対するルールブック（経費申請ルール集）
- ロール：ユーザーへの役職付与（カードキーの種類）
- 3つが連携：ロールで役職判断 → Gate/Policy がロールを使って権限を決める

### 多対多（たたいた）と中間テーブル
- 多対多 = 両方向で複数持てる関係
- 中間テーブルが必要な理由：データベースは多対多を直接表現できない
- 命名規則：2つのモデル名をアルファベット順・単数形でつなぐ

### attach・detach・sync の使い分け
- attach = 追加する（既存は残る）
- detach = 削除する
- sync = 指定したものだけに置き換える（チェックボックスの保存に使う）

---

## 次への課題

### Day8の学習内容
- ファイルアップロードと画像処理
- 実用性の高い投稿機能

### 技術的な理解の深化
- Gate・Policy・ロールの「なぜそう書くか」をLaravelコーヒーで言語化する
- 多対多の操作（attach・sync・detach）をLaravelコーヒーで復習する
- Day7全体を通じてコピペ作業になった反省を活かし、次のDayからは「処理の流れを日本語で説明してから実装する」習慣をつける

---

**作成日：** 2026/04/22
**作成者：** Kloge
**対象：** Laravel-Practice-Support プロジェクト Day7