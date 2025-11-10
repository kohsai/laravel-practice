<?php

/**
 * Step4-03: Fortify の設定ファイル publish 処理（教材解説）
 * ---------------------------------------------
 * このステップでは、Fortify の ServiceProvider を publish し、
 * `config/fortify.php` を生成する作業を行います。
 *
 * ✅ コマンドの目的：
 * Fortify のデフォルト設定ファイルを自分のアプリにコピー（publish）し、
 * 認証機能の有効化・調整ができるようにします。
 *
 * ✅ 実行コマンド：
 * php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
 *
 * 🔍 実行後に確認すべき点：
 * - `config/fortify.php` ファイルが新たに生成されていること
 * - このファイルで認証関連の設定（register/login/logout/password reset など）が行えるようになること
 *
 * 🧠 ポイント：
 * - Laravelでは `vendor:publish` により、パッケージが提供するリソース（config, views, translationsなど）をアプリにコピーできる
 * - Fortify の場合、ServiceProvider名で指定してpublishする
 * - これにより、Fortify の設定内容をプロジェクトごとに自由にカスタマイズできる
 */
