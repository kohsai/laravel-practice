@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
    <h2>タスク一覧</h2>

    <p><a href="{{ route('tasks.create') }}">+ 新規作成</a></p>

    @if ($tasks->isEmpty())
        <p>登録されたタスクはありません。</p>
    @else
        <ul>
            @foreach ($tasks as $task)
                <li>
                    <strong>
                        <a href="{{ route('tasks.show', ['task' => $task->id]) }}">{{ $task->title }}</a>
                    </strong>
                    &nbsp;|&nbsp;
                    <a href="{{ route('tasks.edit', ['task' => $task->id]) }}">編集</a>

                    <form action="{{ route('tasks.destroy', ['task' => $task->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('削除してよろしいですか？')">削除</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
