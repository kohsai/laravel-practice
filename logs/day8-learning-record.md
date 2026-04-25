# Day8学習記録

## 学習期間

2026/04/23 〜 2026/04/25

## 学習範囲

Step8-01〜Step8-04（ファイルアップロードと画像処理）

---

## 主な学習内容

### Step8-01：Laravelのストレージの仕組みを理解する

- Laravelのフォルダ構成（storage/app/public/の役割）
- storageリンク（シンボリックリンク）の仕組みと作成方法
- .envの FILESYSTEM_DISK=public 設定
- nginxの client_max_body_size を 20M に変更

**作成ファイル：** なし（設定変更のみ）

**教材ファイル：** `src/day8/day8_step8-01_storage_basics.php`

### Step8-02：ファイルアップロードの実装（支出に画像を添付）

- expensesテーブルへの image_path カラム追加（マイグレーション作成・実行）
- Expense モデルの $fillable に image_path を追加
- create.blade.php にファイル選択欄追加（enctype="multipart/form-data"）
- ExpenseController の store() に画像保存処理を追加（hasFile / store）
- index.blade.php に画像表示欄追加（Storage::url()）

**作成ファイル：**
- `database/migrations/XXXX_add_image_path_to_expenses_table.php`

**教材ファイル：** `src/day8/day8_step8-02_file_upload.php`（統合版）

**質問2問：**
1. StoreUserRequest の `integer` → `numeric` に修正が必要だった理由
2. routes/web.php の `->middleware('auth')` が未設定だった件

### Step8-03：画像の更新・削除

- edit.blade.php に画像フォーム追加（enctype・現在の画像表示・削除チェックボックス）
- update() に画像の3パターン処理を実装
  - パターン①：削除チェックあり → 古い画像削除・image_path を null に
  - パターン②：新しい画像あり → 古い画像削除・新しい画像を保存
  - パターン③：変更なし → そのまま更新
- Storage::disk('public')->delete() による古いファイルの削除

**教材ファイル：** `src/day8/day8_step8-03_image_edit_delete.php`（統合版）

**質問1問：**
1. 動作確認URLが `/expenses/index` ではなく `/expenses` である理由（resourceルートの仕様）

### Step8-04：バリデーション（画像の形式・サイズ制限）

- StoreUserRequest に画像バリデーションルールを追加（nullable / image / mimes / max）
- image・mimes・max・nullable 各ルールの意味と使い分けを理解
- PHPの upload_max_filesize とLaravelのバリデーションの違いを理解
- GodeVenでのカスタムメッセージ表示方針（Alpine.jsで事前チェック）

**教材ファイル：** `src/day8/day8_step8-04_image_validation.php`（統合版）

**質問2問：**
1. 7MBの画像で「Imageのアップロードに失敗しました。」が表示された理由（upload_max_filesize = 2M でPHPが弾く）
2. GodeVenでカスタムメッセージを表示するための方法（PHP設定 or Alpine.js事前チェック）

---

## 主なエラーと解決方法

### エラー1：StoreUserRequest の integer → numeric

**発生時期：** Step8-02

**原因：**
amount のバリデーションルールが `integer`（整数のみ）になっていたが、HTMLのnumber入力は小数点付きの値を送ることがあるため `numeric`（数値）が正しかった。

**解決：**
`'amount' => 'required|numeric|min:1|max:9999999'` に修正。

**教訓：**
教材作成前に既存ファイルの内容を確認する（laravel_learning_system_v1_18.md 2.2節のルール）。

### エラー2：7MB画像でカスタムメッセージが表示されない

**発生時期：** Step8-04

**原因：**
PHPの `upload_max_filesize = 2M` の設定により、7MBのファイルはLaravelのバリデーションに届く前にPHPが弾く。Laravelのカスタムメッセージではなくデフォルトメッセージが表示された。

**解決：**
動作確認の目的（2MB超を弾く）は達成されているため許容。GodeVenでは Alpine.js による送信前チェックで対応予定。

**教訓：**
ファイルアップロードには nginx・PHP・Laravel の3段階の制限がある。カスタムメッセージを表示するにはLaravelまで届く必要がある。

---

## 作成・編集ファイル

### 教材ファイル（4件）
- `src/day8/day8_step8-01_storage_basics.php`
- `src/day8/day8_step8-02_file_upload.php`
- `src/day8/day8_step8-03_image_edit_delete.php`
- `src/day8/day8_step8-04_image_validation.php`

### Laravelファイル（新規作成）
- `database/migrations/XXXX_add_image_path_to_expenses_table.php`

### Laravelファイル（編集）
- `app/Models/Expense.php`（$fillable に image_path 追加）
- `app/Http/Controllers/ExpenseController.php`（store・update に画像処理追加）
- `app/Http/Requests/StoreUserRequest.php`（image バリデーション追加）
- `resources/views/expenses/create.blade.php`（画像フォーム追加）
- `resources/views/expenses/edit.blade.php`（画像フォーム・削除チェック追加）
- `resources/views/expenses/index.blade.php`（画像表示追加）
- `docker/nginx/default.conf`（client_max_body_size 20M 追加）
- `.env`（FILESYSTEM_DISK=public に変更）

---

## 環境の状態

- nginx: client_max_body_size を 20M に設定（docker/nginx/default.conf）
- .env: FILESYSTEM_DISK=public に変更
- storage リンク作成済み（public/storage → storage/app/public）

---

## Git管理

### ブランチ
- `day8-file-upload` ブランチで作業
- mainブランチへのマージ：未実施（Day完了後に実施）

### 主なコミット
1. `Step8-02: add image upload to expenses`
2. `Step8-03: add image update and delete to expenses`
3. `Step8-04: add image validation to StoreUserRequest`

---

## 学んだ重要な概念

### storageリンク（シンボリックリンク）
public/storage → storage/app/public へのショートカット。ブラウザからアクセスできるURLとLaravelが保存するパスをつなぐ仕組み。

### ファイルアップロードの3段階制限
nginx（client_max_body_size）→ PHP（upload_max_filesize）→ Laravel（バリデーション）の順に処理される。カスタムメッセージを表示するにはLaravelまで届く必要がある。

### 画像更新の3パターン
編集時は「削除チェックあり」「新画像あり」「変更なし」の3パターンを条件分岐で処理する。古い画像の削除忘れを防ぐことがポイント。

---

## 次への課題

### 次のDayの学習内容
Day9：コンポーネントとレイアウト再利用（Blade再利用・共通部品設計）

### 技術的な理解の深化
- PHPの upload_max_filesize 設定変更方法（php.ini）
- Alpine.jsによるファイルサイズの事前チェック実装（GodeVen開発時）