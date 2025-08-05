# Laravel-practice

このプロジェクトは、Laravelの学習と実践のための開発環境です。
Dockerを用いて Laravel 開発環境を構築し、日々の学習記録を `logs/` ディレクトリに記録しています。

---

## 📚 学習記録

- Day1: Laravel環境構築（Docker, GitHub初期設定など）


- Day2: 基本ルーティングとBlade（ルート定義 / Controller経由 / View表示）
  ↳ [logs/day2-routing-blade.md](logs/day2-routing-blade.md)


- Day3: RESTfulルーティングとリソースコントローラの基礎
  - Route::resource による7アクションの自動定義
  - TaskController に各アクションメソッド（index, create, store 等）を実装
  - Bladeビュー `index.blade.php`, `create.blade.php` を作成
  - create → store のフォーム送信とController処理の連携確認
  ↳ [logs/day3-routing-restful.md](logs/day3-routing-restful.md)


---

## 📁 ディレクトリ構成

```
Laravel-practice/
├── docker/             # nginx, php, mysql, phpmyadmin用構成
├── src/laravel/        # Laravelアプリケーション本体
├── .env                # Git対象外（.gitignore済み）
├── docker-compose.yml  # コンテナ定義ファイル
└── README.md
```

---

## 🚀 環境構築手順（初回のみ）

```bash
git clone https://github.com/kohsai/laravel-practice.git
cd laravel-practice
docker-compose up -d --build
```

ブラウザで以下にアクセス：

- http://localhost:8080 （Laravelアプリ）
- http://localhost:8081 （phpMyAdmin）

---

## 🔧 パーミッション設定（初回セットアップ後）

```bash
# 📁 パーミッション設定（初回セットアップ後）

# Docker環境では、Laravelの一部ディレクトリに書き込み権限が必要です。
# 以下のコマンドを実行し、storage/ および bootstrap/cache/ に適切な権限を設定してください。

cd src/laravel
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 💡補足：
# www-data は通常、PHP-FPM や nginx が使用するユーザーです。
# この設定を行わないと、laravel.log への書き込みエラーや
# Blade コンパイルエラーが発生する可能性があります。
```

---

## ⚠️ artisanコマンドの実行に関する注意（Docker + WSL環境）

```bash
# ⚡ artisanコマンドを実行する際の推奨方法と注意点

# Laravelの artisan コマンドは「ホスト（WSL）側のターミナル」から実行するのが安全です。
# 例：コントローラやモデルを作成する場合

cd src/laravel
php artisan make:controller SampleController

# ✅ 理由：
# Dockerコンテナ内で artisan を実行すると、パーミッションの不整合やファイル所有者の違いにより、
# 作成されたファイルに不具合が生じる可能性があります（例：www-data 所有になるなど）。
# ホスト側で実行することで、WSLユーザーの権限でファイルが作成され、編集トラブルを防げます。
```
