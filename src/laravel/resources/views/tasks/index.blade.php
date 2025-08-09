@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
    <h2>タスク一覧</h2>

    @php
        $tasks = [
            ['id' => 1, 'title' => 'サンプルタスク #1'],
            ['id' => 2, 'title' => 'サンプルタスク #2'],
            ['id' => 3, 'title' => 'サンプルタスク #3'],
        ];
    @endphp

    <ul>
        @foreach ($tasks as $task)
            <li>
                <strong>{{ $task['title'] }}</strong>
                &nbsp;|&nbsp;
                <a href="{{ route('tasks.edit', ['task' => $task['id']]) }}">編集</a>

                <form action="{{ route('tasks.destroy', ['task' => $task['id']]) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('削除してよろしいですか？')">削除</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection