<?php

/**
 * Step4-01: Fortifyの概要とインストール準備（実行ログ）
 * --------------------------------------------
 * - FortifyはLaravelの公式認証パッケージ
 * - Laravel BreezeやJetstreamの下層で動作
 * - Fortify単体で導入する方針（ビューは自作）
 * - Composer経由で導入するため、composer.jsonの依存関係に追加された
 * - この時点ではFortifyのServiceProviderは自動登録されていない
 *
 * 実行コマンド:
 *   composer require laravel/fortify
 *
 * 結果:
 *   ✅ 正常にインストール完了
 *   ✅ vendor/ 配下に `laravel/fortify/` が追加された
 */
