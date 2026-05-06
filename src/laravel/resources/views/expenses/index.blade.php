@extends('layouts.app')

@section('title', '支出一覧')

@section('content')

    <h1>支出一覧</h1>

    <a href="{{ route('expenses.create') }}">新しい支出を追加する</a>

    <x-alert type="success" :message="session('success')" />

    @if ($expenses->isEmpty())
        <p>支出データがまだありません。</p>
    @else
        <x-card>
            <table border="1" cellpadding="8" cellspacing="0">
                <tr>
                    <th>日付</th>
                    <th>カテゴリ</th>
                    <th>金額</th>
                    <th>説明</th>
                    <th>タグ</th>
                    <th>画像</th>
                    <th>操作</th>
                </tr>
                @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->spent_at }}</td>
                        <td>{{ $expense->category }}</td>
                        <td>{{ number_format($expense->amount) }}円</td>
                        <td>{{ $expense->description }}</td>
                        <td>
                            @foreach ($expense->tags as $tag)
                                <span style="background: #dbeafe; padding: 2px 8px; border-radius: 4px; margin-right: 4px;">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            @if ($expense->image_path)
                                <img src="{{ Storage::url($expense->image_path) }}" alt="支出画像" width="100">
                            @endif
                        </td>


                        <td>
                            <a href="{{ route('expenses.edit', $expense) }}">編集</a>
                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('削除しますか？')">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </x-card>
    @endif
@endsection
