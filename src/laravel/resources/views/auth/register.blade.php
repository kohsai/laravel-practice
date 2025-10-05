@extends('layouts.app')

@section('title', 'ユーザー登録')

@section('content')
<h2>ユーザー登録</h2>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <input type="text" name="name" placeholder="名前">
    <input type="email" name="email" placeholder="メールアドレス">
    <input type="password" name="password" placeholder="パスワード">
    <input type="password" name="password_confirmation" placeholder="パスワード（確認）">
    <button type="submit">登録</button>
</form>
@endsection
