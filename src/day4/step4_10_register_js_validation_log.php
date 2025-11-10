<?php

/**
 * Step4-10: Fortify画面群のバリデーション統一・JSリアルタイム対応（実行ログ）
 * -------------------------------------------------------------------------
 * 対象：
 *   - login.blade.php（4-10a）
 *   - forgot-password.blade.php（4-10b）
 *   - reset-password.blade.php（4-10c）
 *   - register.blade.php + app.js（4-10d）

 * ✅ 主な修正内容
 * -------------------------
 * ◉ Blade構造の共通化：
 *   - 各inputに required, minlength, maxlength, inputmode, aria-invalid 等を統一適用
 *   - Laravelのバリデーション表示（@error）＋ JS用エラーdiv（id="*-error"）の併用
 *   - aria-describedby, role="alert", aria-live="polite" によるアクセシビリティ考慮

 * ◉ JSバリデーションの導入（app.js）：
 *   - DOMContentLoaded後、input[required] を監視し checkValidity()
 *   - aria-invalid をリアルタイム更新
 *   - エラーメッセージdivの表示/非表示制御
 *   - password_confirmation の一致チェックを個別実装（register専用）

 * ✅ 表示確認：
 * -------------------------
 * - 入力不正時に即座に赤字エラーメッセージ表示
 * - 入力中の即時フィードバック、blur時の補正動作
 * - エラーがない場合はメッセージ非表示（初期は aria-invalid="false"）

 * ✅ 実装方針の統一：
 * -------------------------
 * - reset-password と register の password_confirmation 挙動を完全統一
 *   → 「未入力時は非表示」「一致しない時のみエラー文表示」
 * - JSで textContent を都度書き換える仕様に変更（汎用化）
 * - LaravelのバリデーションとJSバリデーションの両立を実現

 * 🧪 動作確認済みブラウザ：
 *   - Chrome最新版（2025年11月時点）
 *   - スーパーリロード（Ctrl + Shift + R）でCSS反映確認済

 * 📌 補足：
 *   - `app.blade.php` にて @vite 指定済み
 *   - resources/css/app.css は既存通り（Tailwind風クラスは今後導入予定）
 */
