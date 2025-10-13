@extends('layouts.app')

@section('title', 'ユーザー登録')

@section('content')
    <h2>ユーザー登録</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <label for="name">名前</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="山田 太郎"
                required
                minlength="2"
                maxlength="32"
                pattern="[ぁ-んァ-ン一-龥a-zA-Z0-9\s]+"
                inputmode="text"
                title="2〜32文字の名前（漢字・ひらがな・英数字）が使えます"
            >
            @error('name')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email">メールアドレス</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="you@example.com"
                required
                autocomplete="email"
                inputmode="email"
                title="有効なメールアドレスを入力してください"
            >
            @error('email')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">パスワード</label>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="8文字以上"
                required
                minlength="8"
                maxlength="64"
                autocomplete="new-password"
                inputmode="text"
                title="8文字以上の英数字でパスワードを入力してください"
            >
            @error('password')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password_confirmation">パスワード（確認）</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                placeholder="もう一度入力"
                required
                minlength="8"
                maxlength="64"
                autocomplete="new-password"
                inputmode="text"
                title="確認用にも同じパスワードを入力してください"
            >
        </div>

        <button type="submit">登録</button>
    </form>
@endsection
