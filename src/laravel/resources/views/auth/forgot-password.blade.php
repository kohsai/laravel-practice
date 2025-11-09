@extends('layouts.app')

@section('title', 'パスワード再発行リクエスト')

@section('content')


    <h2>パスワード再発行リンクの送信</h2>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- メール送信後のステータスメッセージ -->
        @if (session('status'))
            <div style="color: green;">
                {{ session('status') }}
            </div>
        @endif

        <!-- メールアドレス入力欄 -->
        <label for="email">登録メールアドレス:</label>
        <input id="email" type="email" name="email" placeholder="例）test@example.com" value="{{ old('email') }}"
            required>

        @error('email')
            <div style="color:red">{{ $message }}</div>
        @enderror

        <button type="submit">送信</button>
    </form>
@endsection
