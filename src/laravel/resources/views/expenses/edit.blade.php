<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>支出を編集</title>
</head>

<body>
    <h1>支出を編集する</h1>

    @if ($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('expenses.update', $expense) }}">
        @csrf
        @method('PUT')

        <div>
            <label>カテゴリ</label><br>
            <input type="text" name="category" value="{{ old('category', $expense->category) }}">
            @error('category')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>金額（円）</label><br>
            <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}">
            @error('amount')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>説明（任意）</label><br>
            <textarea name="description">{{ old('description', $expense->description) }}</textarea>
            @error('description')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>日付</label><br>
            <input type="date" name="spent_at" value="{{ old('spent_at', $expense->spent_at) }}">
            @error('spent_at')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>タグ（複数選択可）</label><br>
            @foreach ($tags as $tag)
                <label style="margin-right: 12px;">
                    <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" @checked(in_array($tag->id, $selectedTagIds ?? []))>
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>

        <button type="submit">更新する</button>
    </form>

    <a href="{{ route('expenses.index') }}">一覧に戻る</a>
</body>

</html>
