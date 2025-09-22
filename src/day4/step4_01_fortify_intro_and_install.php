<?php
// 📗 step4_01_fortify_intro_and_install.php
// Laravel Fortify の概要とインストール手順

/**
 * 🟩 Fortifyとは？
 *
 * Laravel Fortifyは、認証機能の「バックエンドのみ」を提供する公式パッケージ。
 * Laravel BreezeやJetstreamのようにUIは含まず、純粋に認証ロジックを管理する。
 * フロントエンドは自前Blade or SPAで自由に構築する場合に最適。
 *
 * 🔐 提供される機能（一部）
 * - ユーザー登録
 * - ログイン / ログアウト
 * - パスワードリセット（メール送信含む）
 * - メール認証（verify）
 * - 二要素認証（2FA）など（必要に応じて有効化）
 *
 * Fortifyは、ServiceProviderによってルートや動作を登録する。
 */

// -------------------------------
// 📦 Fortifyのインストールコマンド
// -------------------------------

// composerでパッケージを導入
// ✅ Laravel 10.x 対応済
// ターミナルで実行：
/*
docker-compose exec php composer require laravel/fortify
*/

// -------------------------------
// 🧩 FortifyのServiceProvider登録
// -------------------------------

// config/app.php の 'providers' に以下を追加（Laravel10では自動登録のため不要なことも）：
/*
App\Providers\FortifyServiceProvider::class,
*/

// -------------------------------
// 📦 Fortifyの設定ファイル公開
// -------------------------------

/*
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
*/

// 実行後：config/fortify.php が生成される

// -------------------------------
// 🔧 Fortifyの有効化とルーティング自動登録
// -------------------------------

/**
 * App\Providers\FortifyServiceProvider を作成し、
 * boot() メソッドで Fortify::loginView() などを設定
 *
 * Fortify の各機能は `config/fortify.php` で有効化・無効化できる。
 *
 * ➕ 例：
 * 'features' => [
 *     Features::registration(),
 *     Features::resetPasswords(),
 * ],
 */
