# Day4: Fortify認証機能の導入とバリデーションUIの統一

## 📌 実施内容

- Laravel Fortify を composer 経由で導入（UIなしの認証バックエンド）
- vendor:publish により `config/fortify.php` を生成
- FortifyServiceProvider を用意し、`boot()` に各ビューのルーティングを定義（loginView 等）
- Fortify の features() 設定で必要な機能を明示（登録・ログイン・リセット等）
- 認証4画面（login / register / forgot-password / reset-password）を Blade で自作
- 各画面にアクセシビリティ属性（`aria-invalid`, `aria-describedby`, `role="alert"`）を適用
- JSリアルタイムバリデーション（app.js）を実装し、`checkValidity()` による即時チェックを導入
- `password_confirmation` の一致チェックをJSで実装し、register / reset で挙動統一
- Laravelの `@error` 表示と `<div id="*-error">` によるJS対応を両立

## 📁 対象ファイル

- config/fortify.php
- app/Providers/FortifyServiceProvider.php
- routes/web.php
- resources/views/auth/login.blade.php
- resources/views/auth/register.blade.php
- resources/views/auth/forgot-password.blade.php
- resources/views/auth/reset-password.blade.php
- resources/views/layouts/app.blade.php
- resources/js/app.js
- resources/css/app.css（存在確認・vite連携）
- src/day4/step4_01〜step4_10 各教材ファイル（*.php, *_log.php）

## 🔗 GitHubリンク

- [day4-fortify-auth ブランチ（GitHub）](https://github.com/kohsai/laravel-practice/tree/day4-fortify-auth)
