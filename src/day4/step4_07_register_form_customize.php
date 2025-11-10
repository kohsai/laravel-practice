<?php

/**
 * Step4-07: register.blade.php のカスタマイズ（Fortify用）
 * --------------------------------------------------------
 * - Laravel Fortify のユーザー登録画面も自作する必要があるため、login.blade.php と同様の構成で設計。
 * - このステップでは、**Bladeテンプレートの構造とバリデーション枠組みの整備**を行う。
 *
 * ✅ 主な内容：
 * - name / email / password / password_confirmation の4項目を用意
 * - それぞれに `required`, `minlength`, `maxlength`, `pattern`, `title`, `inputmode`, `aria-*` 属性を適切に設定
 * - Laravelのサーバー側バリデーション結果を表示する `@error` ブロックを併設
 * - 今後のJS対応に備えて `<div id="*-error">` をプレースホルダとして挿入
 *
 * ✅ 補足：
 * - この段階では、まだリアルタイムJSは未導入（見た目だけ）
 * - `aria-invalid="false"` を初期状態で固定記述 → JSで動的更新するための準備
 */
