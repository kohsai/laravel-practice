# Laravel-practice

このプロジェクトは、Laravelの学習と実践のための開発環境です。  
Dockerを用いて Laravel 開発環境を構築し、日々の学習記録を `logs/` ディレクトリに記録しています。

---

## 📚 学習記録

- Day1: Laravel環境構築（Docker, GitHub初期設定など）

（今後、Dayごとに追記していく）

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
