<?php

/**
 * Step4-08: forgot-password / reset-password 画面のBlade調整
 * ---------------------------------------------------------
 * - Fortifyでは、パスワード再発行と再設定の2画面（forgot / reset）を自作する必要がある。
 * - どちらも login / register と同様に Blade を拡張し、バリデーション・UI構造を統一する。
 *
 * ✅ forgot-password.blade.php のポイント：
 * - email 入力欄のみ（1フィールド）
 * - `@error('email')` によるエラー表示
 * - `<div id="email-error">` でJS用のバリデーションブロックを追加
 * - 成功時に `session('status')` の表示を行う
 *
 * ✅ reset-password.blade.php のポイント：
 * - email / password / password_confirmation の3フィールド
 * - 各入力に `required`, `aria-*`, `autocomplete`, `minlength` などを付与
 * - `@error` + `<div id="*-error">` を併設し、JSとの両立を意識
 * - トークン（`<input type="hidden" name="token">`）が必要
 *
 * ✅ 補足：
 * - CSSクラスは Tailwind風に設計しつつ、構造の理解を優先
 * - まだリアルタイムJSは動かしていない（次ステップ）
 */
