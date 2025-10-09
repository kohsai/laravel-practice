<?php

return [

    'required' => ':attribute は必須項目です。',
    'string' => ':attribute には文字列を入力してください。',
    'email' => ':attribute には有効なメールアドレスを入力してください。',
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'max' => [
        'string' => ':attribute は :max 文字以内で入力してください。',
    ],
    'confirmed' => ':attribute の確認が一致しません。',

    'attributes' => [
        'title' => 'タイトル',
        'description' => '詳細',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード（確認）',
        'name' => 'ユーザー名',
    ],
];
