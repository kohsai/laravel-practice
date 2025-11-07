{{-- resources/views/auth/login.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto mt-8">
        <h1 class="text-2xl font-bold text-center mb-6">ログイン</h1>

        {{-- 成功メッセージ表示（例：パスワード再設定完了） --}}
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600" role="alert" aria-live="polite">{{-- // ARIA対応：読み上げ可能に --}}
                {{ session('status') }}
            </div>
        @endif

        {{-- ✅ JSでエラーをまとめて表示する領域（今は空） --}}
        <div id="error-summary" class="sr-only" role="alert" aria-live="assertive"></div>


        <form method="POST" action="{{ route('login') }}" novalidate>
            {{-- ✅ JSとの衝突を防ぐためnovalidate付与 --}}
            @csrf

            {{-- メールアドレス入力欄 --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>

                <input id="email"
                name="email"
                type="email"
                required
                autofocus
                {{--✅ エラー時に true に切り替え --}}
                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                {{-- ✅ LaravelエラーとJS補助を両方つなぐ --}}
                aria-describedby="email-error"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                    value="{{ old('email') }}" >

                {{-- Laravel側のサーバーサイドバリデーション --}}
                @error('email')
                    <p class="mt-1 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- ✅ JSリアルタイムバリデーション用 --}}
            <div id="email-error" style="display: none; color: red;" role="alert" aria-live="polite">
                メールアドレスの形式が正しくありません
            </div>

            {{-- パスワード --}}
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">パスワード</label>
                <input id="password"
                name="password"
                type="password"
                required
                minlength="8"
                aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                aria-describedby="password-error"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">

                @error('password')
                    <p class="mt-1 text-sm text-red-600" id="password-error">{{ $message }}</p>
                @enderror
            </div>
                {{-- ✅ JSリアルタイムバリデーション用 --}}
                <div id="password-error" style="display: none; color: red;" role="alert" aria-live="polite">
                    パスワードは8文字以上で入力してください
                </div>


            {{-- ログイン失敗メッセージ （メールアドレスだけエラー）--}}
            @if ($errors->has('email') && !$errors->has('password'))
                <div class="mb-4 text-sm text-red-600">
                    メールアドレスまたはパスワードが正しくありません。
                </div>
            @endif

            {{-- ログインボタン --}}
            <div class="mb-4">
                <button type="submit"
                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    ログイン
                </button>
            </div>

            {{-- パスワード再設定リンク --}}
            <div class="text-center">
                <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">
                    パスワードを忘れましたか？
                </a>
            </div>
        </form>
    </div>
@endsection
