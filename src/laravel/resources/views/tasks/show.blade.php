@extends('layouts.app')

@section('title', 'タスク詳細')

@section('content')
    <h2>タスク詳細</h2>
    <p>ID: {{ $task->id }} のタスクを表示しています（仮）</p>
    <ul>
        <li><strong>タイトル：</strong> {{ $task->title }}</li>
        <li><strong>詳細：</strong>
            {!! nl2br(e($task->description ?? '（未入力）')) !!}
        </li>
    </ul>

@endsection
