@extends('layouts.app')

@section('title', 'タスク編集')

@section('content')
    <h2>タスク編集フォーム</h2>
    <p>ID: {{ $task->id }} のタスクを編集します（仮）</p>

    <form method="POST" action="{{ route('tasks.update', ['task' => $task->id]) }}">
        @csrf
        @method('PUT')

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


        <div>
            <label>タイトル：
                <input id="title" type="text" name="title" value="{{ old('title', $task->title) }}"
                    placeholder="必須・255文字まで">
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror


            </label>
        </div>

        <div>
            <label>詳細：
                <textarea id="description" name="description" rows="4">{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

            </label>
        </div>

        <button type="submit">更新する</button>
    </form>

    <p><a href="{{ route('tasks.index') }}">一覧へ戻る</a></p>

@endsection
