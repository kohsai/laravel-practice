@extends('layouts.app')

@section('title', 'パスワード再発行リクエスト')

@section('content')
<h2>パスワード再発行リンクの送信</h2>
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <input type="email" name="email" placeholder="登録メールアドレス">
    <button type="submit">送信</button>
</form>
@endsection
