@extends('layouts.app')

@section('title', '新しいタスクの作成')

@section('content')
    <h2>新しいタスクを作成</h2>

    <!-- フォーム開始（POST /tasks に送信） -->
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf <!-- CSRFトークンを自動生成 -->

        {{-- バリデーションエラーの表示ブロック（共通） --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <p><strong>入力に誤りがあります。</strong></p>
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <label for="title">タイトル:</label><br>
        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="必須・255文字まで"><br>
        @error('title')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        <br>

        <label for="description">詳細:</label><br>
        <textarea name="description" id="description" rows="4">{{ old('description') }}</textarea><br>
        @error('description')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        <br>

        <button type="submit">保存</button>
    </form>
@endsection
