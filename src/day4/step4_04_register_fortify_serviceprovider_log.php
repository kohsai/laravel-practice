<?php

/**
 * Step4-04: FortifyServiceProvider の登録に関するログ
 * -------------------------------------------------------
 * 作業日：2025-09-22
 * 実行環境：phpコンテナ内（Docker）
 *
 * ✅ 状況確認：
 * - `app/Providers/FortifyServiceProvider.php` は vendor:publish により自動生成済
 * - `config/app.php` に手動登録は不要（ComposerによるPSR-4オートロード対象）
 * - `boot()` メソッド内に Fortify 設定済（CreateNewUser, RateLimiterなど）
 *
 * ✅ その他の確認事項：
 * - `php artisan config:clear` 実行（必要であれば）
 * - `php artisan route:list` では Fortify のルートはまだ表示されない（次ステップで routes を定義）
 */
