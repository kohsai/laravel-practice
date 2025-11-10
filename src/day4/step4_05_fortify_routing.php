<?php

/**
 * Step4-05: Fortifyのルーティング設定と機能の有効化
 * --------------------------------------------------
 * - Fortify は Laravel Breeze 等と異なり、ルーティングを自動登録しない。
 * - 認証ページ（ログイン、登録、パスワード再発行など）へのルートを手動で設定する必要がある。
 *
 * ✅ 主な作業：
 * - `Fortify::loginView()` や `Fortify::registerView()` を ServiceProvider の `boot()` に定義
 * - `routes/web.php` には新たにルート追加は不要（Fortify側で処理）
 * - `config/fortify.php` の `features()` に機能を明示
 *
 * ✅ 機能の有効化例（fortify.php）：
 * 'features' => [
 *     Features::registration(),
 *     Features::resetPasswords(),
 *     Features::emailVerification(),
 * ],
 */
