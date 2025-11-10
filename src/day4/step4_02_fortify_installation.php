<?php

/**
 * Step4-02: Fortifyのインストールと設定ファイル確認（教材）
 * -------------------------------------------------------
 * - Fortifyインストール後、`php artisan vendor:publish` で設定ファイルを生成
 * - `config/fortify.php` が作成され、認証機能の設定が可能に
 * - `features()` 配列を調整することで、使用する機能（登録・パスワードリセット等）を選択可能
 * - 今回は Breeze 等は導入せず、自前のBladeでUIを構築する方針とした
 *
 * 確認ファイル:
 * - config/fortify.php
 * - config/app.php（登録時にはまだ不要）
 */
