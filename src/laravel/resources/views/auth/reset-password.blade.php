@extends('layouts.app')

@section('title', 'パスワード再設定')

@section('content')
    <h2>パスワード再設定</h2>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <!-- トークンをhiddenで送信 -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- メールアドレス -->
        <label for="email">メールアドレス：</label>
        <input id="email" type="email" name="email" placeholder="例）test@example.com" value="{{ old('email') }}"
            required>
        @error('email')
            <div style="color:red">{{ $message }}</div>
        @enderror

        <!-- 新しいパスワード -->
        <label for="password">新しいパスワード：</label>
        <input id="password" type="password" name="password" placeholder="8文字以上のパスワード" required>
        @error('password')
            <div style="color:red">{{ $message }}</div>
        @enderror

        <!-- 確認入力 -->
        <label for="password_confirmation">パスワード（確認）：</label>
        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="再入力してください" required>

        <button type="submit">再設定</button>




    </form>
@endsection
