<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
    <h1>ログイン</h1>

    <!-- セッションメッセージ -->
    @if (session('status'))
        <div style="color: green;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            @error('email')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" placeholder="パスワードを入力" required>
            @error('password')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">ログイン</button>
    </form>
</body>
</html>
