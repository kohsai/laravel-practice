<?php

/**
 * Step4-06: login.blade.php のカスタマイズ（Fortify用）
 * -----------------------------------------------------
 * - Laravel Fortify のログイン画面は自作する前提のため、UIも独自に構成する。
 * - このステップでは、login画面の基本構造を整えつつ、アクセシビリティ（ARIA）と簡易なバリデーションも含める。
 *
 * ✅ 主な内容：
 * - `@extends('layouts.app')` によるレイアウト継承
 * - `<form method="POST" action="{{ route('login') }}">` によるルーティング
 * - CSRF保護（@csrf）
 * - 入力欄：メールアドレス、パスワード（`required`, `inputmode`, `aria-*` などを付与）
 * - `@error('email')`, `@error('password')` によるLaravel側のバリデーション表示
 *
 * ✅ 補足：
 * - JSによるリアルタイムバリデーションはこの段階では未導入（次ステップ）
 * - `aria-invalid`, `aria-describedby`, `role="alert"` などの属性を先に用意しておくことで後のJS連携がしやすくなる
 */
