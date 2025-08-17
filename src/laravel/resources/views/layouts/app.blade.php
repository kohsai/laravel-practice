<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Laravel App')</title>
</head>

<body>

    <header>
        <h1>Laravel練習アプリ</h1>
        <hr>
    </header>

    <main>
        {{-- 成功メッセージの表示（フラッシュ） --}}
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        {{-- 各ページの本文  --}}
        @yield('content')
    </main>

    <footer>
        <hr>
        <small>&copy; {{ date('Y') }} KOH's Laravel-practice</small>
    </footer>

</body>

</html>
