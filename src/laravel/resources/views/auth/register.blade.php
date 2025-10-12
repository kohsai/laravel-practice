@extends('layouts.app')

@section('title', 'ユーザー登録')

@section('content')
    <h2>ユーザー登録</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name">名前</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="山田 太郎" required>
            @error('name')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
            @error('email')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" placeholder="8文字以上" required>
            @error('password')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password_confirmation">パスワード（確認）</label>
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="もう一度入力" required>
        </div>

        <button type="submit">登録</button>
    </form>
@endsection
