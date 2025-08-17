<?php
/*
[教材記録] Step3 教材⑦：DB連動（マイグレーション／モデル／バリデーション）の続き

■ 前提とスキップ
- ブランチ: day3-routing-edit-update（push済／clean）
- 完了: モデル/マイグレ/実行/テーブル確認 → 本教材ではスキップし、未完了の「バリデーション」に着手

■ この教材の目的
- コントローラでの最小バリデーション → FormRequest へ責務移譲
- フォーム側でのエラー表示と old() 復元を確認

■ 実装メモ
1) コントローラ最小実装（store/update）
    $validated = $request->validate([
    'title' => ['required','string','max:255'],
    'description' => ['nullable','string'],
    ]);
→ 失敗時は自動リダイレクト＆$errors/old()が有効に（公式仕様）

2) フォームのエラー表示（create/edit の先頭に差し込み）
    @if ($errors->any()) ... @endif
    各入力の value は old('title', $task->title ?? '') のように記述

3) FormRequest へ移行
    $ php artisan make:request TaskRequest
    - app/Http/Requests/TaskRequest.php に生成（authorize= true, rules=[...])
    - Controller を TaskRequest で型宣言し、$request->validated() を使用

■ 確認観点
- 必須項目未入力でエラー表示される
- 正常入力でDBへ反映される

■ 参考（公式）
- Validation（バリデーションの自動リダイレクト／エラー表示）
    https://laravel.com/docs/12.x/validation  （Laravel Docs / Accessed: 2025-08-13）
- Form Request（生成先, authorize / rules の基本構造）
    https://laravel.com/docs/11.x/validation  （Laravel Docs / Accessed: 2025-08-13）

*/
