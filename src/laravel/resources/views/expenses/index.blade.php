<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>支出一覧</title>
</head>

<body>
    <h1>支出一覧</h1>

    <a href="{{ route('expenses.create') }}">新しい支出を追加する</a>

    @if (session('success'))
        <div style="color: green; border: 1px solid green; padding: 10px; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if ($expenses->isEmpty())
        <p>支出データがまだありません。</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>日付</th>
                <th>カテゴリ</th>
                <th>金額</th>
                <th>説明</th>
                <th>タグ</th>
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
    @endif
</body>

</html>
