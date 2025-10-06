@extends('layouts.app')

@section('title', 'ユーザー登録')

@section('content')
    <h2>ユーザー登録</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" name="name" placeholder="名前" value="{{ old('name') }}">
        @error('name')
            <div style="color:red">{{ $message }}</div>
        @enderror

        <input type="email" name="email" placeholder="メールアドレス" value="{{ old('email') }}">
        @error('email')
            <div style="color:red">{{ $message }}</div>
        @enderror


        <input type="password" name="password" placeholder="パスワード">
        @error('password')
            <div style="color:red">{{ $message }}</div>
        @enderror
        <input type="password" name="password_confirmation" placeholder="パスワード（確認）">
        <button type="submit">登録</button>
    </form>
@endsection
