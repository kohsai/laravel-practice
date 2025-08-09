@extends('layouts.app')

@section('title', 'タスク編集')

@section('content')
    <h2>タスク編集フォーム</h2>
    <p>ID: {{ $id }} のタスクを編集します（仮）</p>

    <form method="POST" action="{{ route('tasks.update', ['task' => $id]) }}">
        @csrf
        @method('PUT')

        <div>
            <label>タイトル：
                <input type="text" name="title" value="サンプルタイトル">
            </label>
        </div>

        <div>
            <label>詳細：
                <textarea name="description">サンプル詳細</textarea>
            </label>
        </div>

        <button type="submit">更新する</button>
    </form>

    <p><a href="{{ route('tasks.index') }}">一覧へ戻る</a></p>

@endsection
