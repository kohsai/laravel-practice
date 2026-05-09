# Day10学習記録

## 学習期間

2026/05/08 〜 2026/05/09

## 学習範囲

Step10-01〜Step10-04（テスト・本番環境準備）

---

## 主な学習内容

### Step10-01：Laravelテストの基礎（PHPUnit・Feature Test）

- PHPUnit（ピーエイチピーユニット）の基本概念を理解した
- Feature Test（フィーチャーテスト）とUnit Test（ユニットテスト）の違いを学んだ
- `phpunit.xml` にSQLite（`:memory:`）設定を追記した
- 既存サンプルテスト2件（Unit・Feature）を実際に実行してパスを確認した
- `use RefreshDatabase;` トレイトの概念を理解した（実際の追記はStep10-02）

**教材ファイル：** `src/day10/day10_step10-01_testing_basics.php`

**編集ファイル：**
- `src/laravel/phpunit.xml`（SQLite設定のコメントアウト解除）

**質問1問：**
1. `use RefreshDatabase;` は今変更しなくてよいか → Step10-02で行うことを確認

---

### Step10-02：実際のテストを書く

- `ExpenseFactory.php` を新規作成し、テスト用ダミーデータの生成方法を学んだ
- `ExpenseTest.php` を新規作成し、Feature Testの実際の書き方を学んだ
- `actingAs($user)` でログイン状態を作る方法を理解した
- `assertStatus(200)` / `assertDatabaseHas` の使い方を確認した
- `use RefreshDatabase;` を実際のテストファイルに追記した
- 理解度：「何となくわかった」レベル（説明できるレベルには未到達）
- KOHさん自身の評価：「ただのコピペだった」→ GodeVen開発時に見返す教材として位置づけ

**教材ファイル：** `src/day10/day10_step10-02_writing_tests.php`

**作成ファイル：**
- `src/laravel/database/factories/ExpenseFactory.php`
- `src/laravel/tests/Feature/ExpenseTest.php`

**質問なし（統合版作成なし）**

---

### Step10-03：本番環境の設定

- ローカル環境（`APP_ENV=local`）と本番環境（`APP_ENV=production`）の違いを学んだ
- `APP_DEBUG=false` の重要性とセキュリティリスクを理解した
- `config:cache` / `route:cache` によるパフォーマンス改善を学んだ
- `.env` をGitHubに上げてはいけない理由とリスクを理解した
- Laravelのサポートポリシー（Laravel 10のセキュリティサポート期限）を確認した
- GitHubへの `.env` 誤コミット問題と対処法（履歴削除の難しさ）を議論した
- 「GodeVen開発前・運用チェックリスト」の作成をDay10完了後に行う方針を決定した

**教材ファイル：** `src/day10/day10_step10-03_production_env.php`

**質問なし（統合版作成なし）**

---

### Step10-04：デプロイの全体像を知る（座学）

- VPS（バーチャル・プライベート・サーバー）の概念を理解した
- SSH（セキュアシェル）によるVPS遠隔操作の仕組みを理解した
- Xserver VPSを使ったLaravelデプロイの全体ステップ（フェーズ1〜3）を把握した
- ローカル環境と本番環境の違いを整理した
- GodeVen本番公開チェックリストを教材にまとめた
- Xserver VPS料金の最新情報を確認：Docker使用時は4GBプラン推奨（月額約1,500〜2,000円）
- 実際のデプロイ操作はGodeVen開発開始時に行う方針を確認した

**教材ファイル：** `src/day10/day10_step10-04_deploy_overview.php`

**質問なし（統合版作成なし）**

---

## 主なエラーと解決方法

### エラー1：VSCode起動時のWSL接続エラー

**発生時期：** Step10-03作業中

**原因：**
VSCodeのアップデート直後にWSLサーバー側のコンポーネントが古いままになり、接続が切れた。

**解決：**
VSCodeを完全に終了し、再起動して再接続。それでも解決しない場合はPowerShellで `wsl --shutdown` を実行してWSLを再起動する。

**教訓：**
VSCodeのリリースノートが出た直後はWSL接続が不安定になることがある。焦らず再起動で対応できる。

---

## 作成・編集ファイル

### 教材ファイル（4件）
- `src/day10/day10_step10-01_testing_basics.php`
- `src/day10/day10_step10-02_writing_tests.php`
- `src/day10/day10_step10-03_production_env.php`
- `src/day10/day10_step10-04_deploy_overview.php`

### Laravelファイル（新規作成）
- `src/laravel/database/factories/ExpenseFactory.php`
- `src/laravel/tests/Feature/ExpenseTest.php`

### Laravelファイル（編集）
- `src/laravel/phpunit.xml`（SQLite設定のコメントアウト解除）

---

## Git管理

### ブランチ
- `day10-testing-deploy` ブランチで作業
- mainブランチへのマージ：完了

### 主なコミット
1. `day10_step10-01_testing_basics.php`
2. `day10_step10-02_writing_tests.php`
3. `day10_step10-03_production_env.php`
4. `day10_step10-04_deploy_overview.php`

---

## 学んだ重要な概念

### PHPUnit / テストの考え方
自動テストは「コードが正しく動くかを自動で確認する仕組み」。GodeVenのMVP段階では使用頻度は低いが、概念として把握した。Feature TestはHTTPリクエストを模擬して動作を確認する。

### APP_DEBUG=false の重要性
本番環境でデバッグモードをオンにすると、エラー発生時にDBパスワードや内部構造が画面に表示されてしまう。本番では必ず `false` に設定する。

### .env のGit管理リスク
一度GitHubにプッシュしてしまうと、削除しても履歴に残る。`.gitignore` で最初から除外することが絶対に必要。

### VPS vs レンタルサーバー
VPSは「一軒家を借りる」イメージ。Dockerを自由にインストールでき、ローカル環境と同じ構成で本番運用できる。Xserver VPSはLaravelテンプレートあり・日本語サポートありで個人開発に最適。

---

## 次への課題

### 次のマイルストーン
- Laravel-practice Day1〜10 完了（✅ 完了）
- Alpine.js学習（1週間程度）
- GodeVenプロジェクト新規作成（Laravel 12/13）
- Xserver VPS契約・デプロイ（Klogeがサポート）
- GodeVen（Stemme）のMVP開発開始

### 技術的な理解の深化
- テスト（PHPUnit）：GodeVen開発時に実際に書きながら理解を深める
- デプロイ手順：VPS契約時に実際の操作で習得する
- Xserver VPS推奨プラン：Docker使用時は4GBプランを選択する（Step10-04教材の「2GBで十分」という記述は要注意）