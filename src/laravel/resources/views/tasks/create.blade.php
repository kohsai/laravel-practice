@extends('layouts.app')

@section('title', '新しいタスクの作成')

@section('content')
    <h2>新しいタスクを作成</h2>

    <!-- フォーム開始（POST /tasks に送信） -->
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf <!-- CSRFトークンを自動生成 -->

        <label for="title">タイトル:</label><br>
        <input type="text" name="title" id="title"><br><br>

        <label for="description">詳細:</label><br>
        <textarea name="description" id="description"></textarea><br><br>

        <button type="submit">保存</button>
    </form>
@endsection