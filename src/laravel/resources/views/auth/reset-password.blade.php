@extends('layouts.app')

@section('title', 'パスワード再設定')

@section('content')
    <h2>パスワード再設定</h2>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="email" name="email" placeholder="メールアドレス">
        <input type="password" name="password" placeholder="新しいパスワード">
        <input type="password" name="password_confirmation" placeholder="パスワード（確認）">
        <button type="submit">再設定</button>
    </form>
@endsection
