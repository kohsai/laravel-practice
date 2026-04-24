{{-- シンプルなcreate.blade.php（動作確認用）】

// 以下のHTMLをcreate.blade.phpに貼り付けてください：

────────────────────────────────────────── --}}

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>支出を追加</title>
</head>

<body>
    <h1>支出を追加する</h1>

    @if ($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label>カテゴリ</label><br>
            <input type="text" name="category" value="{{ old('category') }}">
            @error('category')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>金額（円）</label><br>
            <input type="number" name="amount" value="{{ old('amount') }}">
            @error('amount')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>

            <label>説明（任意）</label><br>
            <textarea name="description">{{ old('description') }}</textarea>
            @error('description')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>日付</label><br>
            <input type="date" name="spent_at" value="{{ old('spent_at') }}">
            @error('spent_at')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        <div><label>タグ（複数選択可）</label><br>
            @foreach ($tags as $tag)
                <label style="margin-right: 12px;">
                    <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}">
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>

        <div>
            <label for="image">画像（任意）</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>

        <button type="submit">登録する</button>
    </form>
</body>

</html>
