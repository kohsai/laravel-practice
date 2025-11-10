@extends('layouts.app')

@section('title', 'パスワード再設定')

@section('content')
    <div class="max-w-md mx-auto mt--8">
        <h1 class="text-2xl font-bold text-center mb-6">パスワード再設定</h1>

        {{-- 成功メッセージ --}}
        @if (session('status'))
            <div class="mb-4 test-sm text-green-600" role="alert" aria-live="polite">
                {{ session('status') }}
            </div>
        @endif


        <form method="POST" action="{{ route('password.update') }}" novalidate>
            @csrf
            {{-- トークンをhiddenで送信 --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- メールアドレス --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
                <input id="email" name="email" type="email" required
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" aria-describedby="email-error"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                    value="{{ old('email') }}" />

                @error('email')
                    <p class="mt-1 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </div>
            <div id="email-error" style="display: none; color: red;" role="alert" aria-live="polite">
                メールアドレスの形式が正しくありません
            </div>


            {{--  新しいパスワード --}}
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">新しいパスワード</label>
                <input id="password" name="password" type="password" required minlength="8"
                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" aria-describedby="password-error"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500" />

                @error('password')
                    <p class="mt-1 text-sm text-red-600" id="password-error">{{ $message }}</p>
                @enderror
            </div>
            <div id="password-error" style="display: none; color: red;" role="alert" aria-live="polite">
                パスワードは8文字以上で入力してください
            </div>

            {{-- パスワード確認 --}}
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium test-gray-700">パスワード（確認）：</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8"
                    aria-describedby="password_confirmation-error"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500" />
                {{-- Laravel側のバリデーション（id無し） --}}
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600"> {{ $message }}</p>
                @enderror
            </div>
            {{-- JSリアルタイムチェック用（id有り） --}}
            <div id="password_confirmation-error" style="display: none; color:red; " role="alert" aria-live="polite">
                パスワードが一致しません
            </div>

            {{-- 再設定ボタン --}}
            <div class="mb-4">
                <button type="submit"
                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">パスワードを再設定する
                </button>
            </div>
        </form>
    </div>
@endsection
