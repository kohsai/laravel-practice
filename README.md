# Laravel-practice

このプロジェクトは、Laravelの学習と実践のための開発環境です。  
Docker（nginx / php / mysql / phpMyAdmin）で構築し、学習ログは `logs/` に記録します。

---

## 🚀 Quick Start（最短手順）

# 1) 取得・起動
git clone https://github.com/kohsai/laravel-practice.git
cd laravel-practice
docker-compose up -d --build

# 2) 初回セットアップ（権限）
#   Laravelは storage / bootstrap/cache に書込が必要
cd src/laravel
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
#   追加（推奨）：WSLユーザーにも書込可（ACL）
#   sudo apt-get update && sudo apt-get install -y acl
sudo setfacl -R -m u:$USER:rwx storage bootstrap/cache
sudo setfacl -dR -m u:$USER:rwx storage/bootstrap/cache

# 3) .env を準備（例：.env.example をコピーして編集）
#   DB接続例：mysql / laravel_db / laravel_user / secret

# 4) ブラウザ
#   アプリ      : http://localhost:8080
#   phpMyAdmin : http://localhost:8081

---

## 🧰 artisan 実行ルール（Docker + WSL）

# 方針：WSLホスト側から実行（権限ねじれ防止）
cd src/laravel

# 設定キャッシュの影響を避けるとき
php artisan config:clear

# DB接続が必要なコマンド（migrate / db:seed / tinker など）は、
# ホストから叩くときだけ DB_HOST を一時上書きする
DB_HOST=127.0.0.1 php artisan migrate
# （.env の DB_HOST=mysql は Docker用としてそのまま維持）

# 参考：状態確認
php artisan migrate:status

---

## 🧩 よくあるトラブルと対処（最小限）

# Permission denied（storage/logs/laravel.log 等）
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo setfacl -R -m u:$USER:rwx storage bootstrap/cache

# SQLSTATE[HY000] [2002]（DBホスト名解決失敗）
# → ホストからDBへは 127.0.0.1:3306 に接続する
DB_HOST=127.0.0.1 php artisan migrate
php artisan config:clear

# コンテナ稼働確認
docker-compose ps

---

## 📚 学習ログ

- Day1: Laravel環境構築（Docker, GitHub初期設定など）

- Day2: 基本ルーティングとBlade（ルート定義 / Controller経由 / View表示）
  ↳ logs/day2-routing-blade.md

- Day3: RESTfulルーティングとリソースコントローラの基礎
  - Route::resource による7アクションの自動定義
  - TaskController の各アクション実装（index / create / store / …）
  - Bladeビュー `index.blade.php`, `create.blade.php`
  - Task のCRUD操作を一貫して実装（create / read / update / delete）
  - TaskRequest による FormRequest バリデーションを導入
  - バリデーションメッセージの日本語化（`resources/lang/ja/validation.php`）
  - `$id` → `Task $task` へのルートモデルバインディングを実施
  ↳ logs/day3-routing-restful.md
  ↳ logs/day3-routing-edit-update.md

- Day4: Laravel Fortify認証機能
  - ログイン・ユーザー登録・パスワードリセット
  ↳ logs/day4-auth-fortify.md

- Day5: Eloquent基礎とリレーション（学習中）
  - Eloquentの基本操作（all / find / where / create / update / delete）
  - モデルのリレーション（hasMany / belongsTo / hasOne / belongsToMany）
  ↳ logs/day5-eloquent.md

---

## 📁 ディレクトリ構成（抜粋）

Laravel-practice/
├── docker/             # nginx, php, mysql, phpmyadmin
├── src/laravel/        # Laravelアプリ本体
├── study/              # 学習復習ファイル
│   └── laravel-coffee/ # Laravelコーヒー（復習・記憶定着）
├── logs/               # 学習ログ
├── docker-compose.yml
└── README.md

---

## 🔒 共有ルール

# .env は Git に含めない（.gitignore 済）／ .env.example を整備
# 本番は本番用 .env を用意し、デプロイ時に以下を実行
php artisan config:cache
# （必要に応じて route:cache / view:cache も）