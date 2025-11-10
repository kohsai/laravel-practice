<?php

/**
 * Step4-04: FortifyServiceProvider の登録と Fortify の有効化（教材解説）
 * ------------------------------------------------------------------
 * このステップでは、Fortify の ServiceProvider を Laravel に登録して、
 * Fortify の機能（ログイン、ユーザー登録など）を有効にします。
 *
 * ✅ FortifyServiceProvider の役割：
 * Fortify のアクション（CreateNewUser, UpdateUserPassword など）と
 * 認証フローに関する設定（RateLimiter, 二要素認証など）をまとめた起点クラスです。
 *
 * ✅ Laravelへの登録方法：
 * FortifyServiceProvider は Fortify の stub が自動で `app/Providers` に生成したものなので、
 * 明示的に `config/app.php` に登録する必要は**通常はありません**。
 *
 * Laravel 8以降では、Fortifyのルーティングやアクションはすべてこの Provider の `boot()` で登録されます。
 *
 * ✅ 開発中に必要な確認：
 * - FortifyServiceProvider に各種 `Fortify::xxxUsing()` が定義されているか？
 * - 必要であれば `AppServiceProvider` の `boot()` などで機能拡張する
 *
 * ✅ 注意点：
 * Fortify は SPA向けやAPI向けにも柔軟に使えるため、view が提供されない。
 * `routes/web.php` に Fortify のルートは自動追加されないため注意（次ステップで解説）
 */
