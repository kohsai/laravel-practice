<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
    <h1>ログイン</h1>

    <!-- ✅ セッションメッセージ -->
    @if (session('status'))
        <div style="color: green;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- ==========================
             メールアドレス入力欄
             ========================== -->
        <div>
            <label for="email">メールアドレス</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="you@example.com"
                required autofocus
                autocomplete="email"
                inputmode="email"
                title="有効なメールアドレスを入力してください"
            >

            {{-- 💬 Laravel側のバリデーション（サーバー側チェック） --}}
            @error('email')
                <div style="color:red">{{ $message }}</div>
            @enderror

            {{-- 💡 JSによるリアルタイムチェック用（クライアント側チェック） --}}
            {{-- 入力中に自動で表示・非表示を切り替えるため、inputの直後が最適 --}}
            <div id="email-error" style="display: none; color: red;">
                メールアドレスの形式が正しくありません
            </div>
        </div>

        <!-- ==========================
             パスワード入力欄
             ========================== -->
        <div>
            <label for="password">パスワード</label>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="パスワードを入力"
                required
                minlength="8"
                maxlength="64"
                autocomplete="current-password"
                inputmode="text"
                title="8文字以上のパスワードを入力してください"
            >

            {{-- Laravel側のバリデーション --}}
            @error('password')
                <div style="color:red">{{ $message }}</div>
            @enderror

            {{-- JSによるリアルタイム補助メッセージ --}}
            <div id="password-error" style="display: none; color: red;">
                パスワードは8文字以上で入力してください
            </div>
        </div>

        <button type="submit">ログイン</button>
    </form>
</body>
</html>
