@extends('layouts.app')

@section('title', 'パスワード再発行リクエスト')

@section('content')
    <h2>パスワード再発行リンクの送信</h2>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- セッションメッセージ -->
        @if (session('status'))
            <div style="color: green;">
                {{ session('status') }}
            </div>
        @endif

        <input type="email" name="email" placeholder="登録メールアドレス" value="{{ old('email') }}">
        @error('email')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </form>
@endsection
