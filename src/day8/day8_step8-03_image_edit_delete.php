<?php

/**
 * 📘 Day8 教材（Step8-03：画像の更新・削除）
 *
 * この教材では「登録済みの支出に画像を追加・差し替え・削除する」機能を実装します
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step8-02では「新規登録時に画像を保存する」ことを学びました。
 * Step8-03では「編集時に画像を差し替える・削除する」ことを学びます。
 *
 * 【今回の課題：なぜ更新・削除が難しいのか？】
 *
 * 新規登録はシンプルです。
 * - 画像あり → 保存する
 * - 画像なし → 保存しない
 *
 * 編集はパターンが増えます：
 * ① 画像を新しく追加する（前は画像なし → 今回追加）
 * ② 画像を差し替える   （前は画像あり → 新しい画像に変える）
 * ③ 画像はそのまま    （前は画像あり → 変更しない）
 * ④ 画像を削除する    （前は画像あり → 削除したい）
 *
 * さらに「差し替える」「削除する」ときは
 * 古い画像をストレージから消す必要があります。
 * 消し忘れると、使われないファイルがどんどん溜まります。
 *
 * 【今回やること】
 * 1. edit.blade.php の修正   → 画像アップロード欄と「削除する」チェックボックスを追加する
 * 2. update() の修正         → ①〜④のパターンを処理するコードを追加する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. edit.blade.php の修正】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【現在の状態（提出されたファイルより）】
 * - enctype が付いていない（ファイルを送れない状態）
 * - 画像フォームがない
 *
 * 【修正点①：フォームタグに enctype を追加する】
 *
 * 「enctype（エンクタイプ）」とは？
 * フォームのデータを「どんな形式で送るか」を指定する設定です。
 * ファイルを送るときは必ず multipart/form-data（マルチパート・フォームデータ）にします。
 * これがないとファイルがサーバーに届きません。
 *
 * 【たとえ話】
 * 普通の荷物（文字データ）は封筒で送れます。
 * でも大きな荷物（ファイル）は段ボール箱で送る必要があります。
 * enctype はその「箱の種類」を指定するものです。
 */

// 【edit.blade.php の修正①】
// <form method="POST" action="{{ route('expenses.update', $expense) }}">
// ↓ 以下に変更する（enctype を追加）

// <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">

/**
 * 【修正点②：現在の画像を表示する欄を追加する】
 *
 * 編集フォームでは「今どんな画像が登録されているか」を表示することが大切です。
 * ユーザーが「この画像を差し替えたい」と判断できるようにします。
 *
 * 【修正点③：新しい画像をアップロードする欄を追加する】
 *
 * type="file" の input 欄を追加します。
 *
 * 【修正点④：「画像を削除する」チェックボックスを追加する】
 *
 * 「削除」ボタンではなくチェックボックスにする理由：
 * フォームの「送信（更新する）」ボタンと一緒に送れるからです。
 * チェックを入れたまま「更新する」を押すと削除されます。
 */

/**
 * 【修正後の edit.blade.php（日付フィールドの下に追加する部分）】
 *
 * 以下のコードを、日付フィールドの </div> の後に追加してください。
 */

// ─────────────────────────────────────────────────────
// 以下のコードをコピーしてedit.blade.phpに追加してください：
// （日付フィールドの </div> の後に追加）
// ─────────────────────────────────────────────────────

/*
<div>
    <label>画像</label><br>

    @if ($expense->image_path)
        <p>現在の画像：</p>
        <img src="{{ Storage::url($expense->image_path) }}" alt="現在の画像" width="150">
        <br>
        <label>
            <input type="checkbox" name="delete_image" value="1">
            この画像を削除する
        </label>
        <br>
    @endif

    <p>新しい画像を選ぶ（任意）：</p>
    <input type="file" name="image" accept="image/*">
    @error('image')
        <span style="color: red;">{{ $message }}</span>
    @enderror
</div>
*/

/**
 * → 上記コードの説明
 *
 * @if ($expense->image_path)
 * └─ 今の支出に画像が登録されていたら表示する
 *
 * Storage::url($expense->image_path)
 * └─ ファイルパス（例：expenses/abc.jpg）を
 *    ブラウザでアクセスできるURL（例：/storage/expenses/abc.jpg）に変換する
 *
 * name="delete_image"
 * └─ このチェックを入れて送信すると、
 *    コントローラーで $request->input('delete_image') が '1' になる
 *
 * accept="image/*"
 * └─ ファイル選択ダイアログで画像ファイルのみ選択できるようにする
 *    （ユーザーへの見た目のフィルターで、サーバー側のバリデーションとは別）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. update() の修正】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【現在の update() の状態（提出されたファイルより）】
 *
 * public function update(StoreUserRequest $request, Expense $expense)
 * {
 *     $this->authorize('update', $expense);
 *     $expense->update($request->validated());
 *     $expense->tags()->sync($request->input('tag_ids', []));
 *     return redirect()->route('expenses.index')->with('success', '支出を更新しました');
 * }
 *
 * → 画像に関する処理がまだ何もない
 *
 * 【修正後の update() の考え方（4つのパターン）】
 *
 * まず「何をすべきか」を日本語で整理します：
 *
 * 判断①：「画像を削除するチェック」が入っているか？
 *   → Yes：古い画像ファイルを削除して、image_path を null にする
 *
 * 判断②：新しい画像ファイルが送られてきたか？
 *   → Yes：古い画像ファイルがあれば削除して、新しい画像を保存して、image_path を更新する
 *
 * 判断③：どちらも該当しない場合
 *   → 画像はそのまま（image_path は変えない）
 *
 * 【コードで書くとこうなる】
 */

/**
 * 【修正後の update() のコード】
 *
 * 以下のコードで update() メソッド全体を置き換えてください。
 */

// ─────────────────────────────────────────────────────
// 以下のコードをコピーして update() を置き換えてください：
// ─────────────────────────────────────────────────────

/*
public function update(StoreUserRequest $request, Expense $expense)
{
    $this->authorize('update', $expense);

    // 【パターン①：「画像を削除するチェック」が入っている場合】
    if ($request->input('delete_image') === '1') {

        // 古い画像ファイルをストレージから削除する
        if ($expense->image_path) {
            Storage::disk('public')->delete($expense->image_path);
        }

        // image_path を null（空）にしてデータを更新する
        $expense->update(array_merge($request->validated(), ['image_path' => null]));

    // 【パターン②：新しい画像ファイルが送られてきた場合】
    } elseif ($request->hasFile('image')) {

        // 古い画像ファイルがあれば削除する
        if ($expense->image_path) {
            Storage::disk('public')->delete($expense->image_path);
        }

        // 新しい画像を保存して、そのパスを取得する
        $imagePath = $request->file('image')->store('expenses', 'public');

        // image_path を新しいパスで更新する
        $expense->update(array_merge($request->validated(), ['image_path' => $imagePath]));

    // 【パターン③：画像に変更なし】
    } else {
        // image_path はそのまま（validated() に含まれないので変わらない）
        $expense->update($request->validated());
    }

    // タグを同期する（タグの処理はパターンに関係なく同じ）
    $expense->tags()->sync($request->input('tag_ids', []));

    return redirect()->route('expenses.index')->with('success', '支出を更新しました');
}
*/

/**
 * 【新しく使うメソッドの説明】
 *
 * Storage::disk('public')->delete($expense->image_path)
 * └─ ストレージからファイルを削除するメソッド
 * └─ disk('public') = 「publicディスク」を使う（storage/app/public/以下）
 * └─ delete() に渡すのは「ファイルパス（相対パス）」
 *    例：'expenses/abc123.jpg'（URLではなくパスを渡す）
 *
 * array_merge($request->validated(), ['image_path' => null])
 * └─ バリデーション済みデータに image_path の値を追加してまとめる
 * └─ null にすると「画像なし」として保存される
 * └─ $imagePath にすると「新しい画像パス」として保存される
 */

/**
 * 【重要】use Storage; の追加を忘れずに
 *
 * Storage ファサード（ファサード = Laravelの便利な道具箱）を使うために
 * ExpenseController.php の先頭に以下を追加する必要があります。
 */

// ─────────────────────────────────────────────────────
// 以下をコピーして ExpenseController.php の先頭の use 文に追加してください：
// ─────────────────────────────────────────────────────

// use Illuminate\Support\Facades\Storage;

/**
 * 追加場所（現在の use 文の下）：
 *
 * use App\Models\Tag;
 * use App\Models\Expense;
 * use App\Http\Requests\StoreUserRequest;
 * use Illuminate\Support\Facades\Storage;  ← これを追加
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. 作業の順番まとめ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【0】事前確認：現在の edit.blade.php に @use(Storage) が必要かを確認する
 *     ← index.blade.php に Storage::url() を使う記述があるので確認します
 *
 * 【1】ExpenseController.php に use Storage を追加する
 *
 * 【2】edit.blade.php を修正する（2箇所）
 *     ① <form> タグに enctype を追加
 *     ② 画像フォームを追加（日付フィールドの下）
 *
 * 【3】update() を修正する
 *     ← 上記「修正後のコード」で全体を置き換える
 *
 * 【4】ブラウザで動作確認する
 *     確認①：既存の支出に画像を追加できるか？
 *     確認②：画像を別の画像に差し替えられるか？
 *     確認③：「削除する」チェックで画像が消えるか？
 *     確認④：画像なしでそのまま更新できるか？
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ❓ 【よくある質問 Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. delete_image を「チェックボックス」にしているのはなぜですか？
 * A1. フォームの「更新する」ボタンと一緒に送れるからです。
 *     チェックを入れた状態で「更新する」を押すと
 *     delete_image=1 がコントローラーに届きます。
 *     別途「削除ボタン」を作るより操作がシンプルになります。
 *
 * Q2. チェックを入れなかった場合、delete_image は何になりますか？
 * A2. チェックボックスは「チェックが入っていない場合、フォームに含まれない」
 *     という仕様があります。
 *     つまり $request->input('delete_image') は null になります。
 *     null === '1' は false なので、パターン①には入りません。
 *
 * Q3. Storage::disk('public')->delete() と Storage::delete() は違いますか？
 * A3. 動作はほぼ同じですが、disk('public') を明示すると
 *     「どのディスクのファイルか」が分かりやすくなります。
 *     .env で FILESYSTEM_DISK=public にしている場合は Storage::delete() でも動きます。
 *     今回は明示的に disk('public') を指定しています。
 *
 * Q4. image_path が null のまま差し替えを試みようとした場合は？
 * A4. パターン②の `if ($expense->image_path)` のチェックで
 *     「古い画像がある場合のみ削除する」という処理をしているので、
 *     image_path が null の場合は削除処理をスキップして新しい画像を保存します。
 *
 * Q5. validated() の結果に image_path が含まれていませんか？
 * A5. StoreUserRequest のバリデーションルールに image_path は含まれていません。
 *     つまり $request->validated() に image_path は入ってきません。
 *     だから array_merge() で手動で追加しています。
 *
 * Q6. edit.blade.php で @use(Storage) は必要ですか？
 * A6. Blade テンプレート内で Storage::url() を使う場合、
 *     Laravel のデフォルト設定では Storage ファサードが自動的に使えます。
 *     @use(Storage) の記述は必要ありません。
 *     ただし index.blade.php ですでに Storage::url() が動作しているなら
 *     edit.blade.php でも同様に動作します。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📚 【用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * enctype（エンクタイプ）
 * └─ フォームのデータ送信形式を指定する属性
 * └─ ファイルを送る場合は必ず multipart/form-data を指定する
 *
 * Storage::disk('public')->delete()（ストレージ・ディスク・デリート）
 * └─ ストレージからファイルを削除するメソッド
 * └─ 引数にはURLではなくファイルパス（相対パス）を渡す
 *
 * delete_image（デリートイメージ）
 * └─ 今回フォームに追加するチェックボックスの name 属性
 * └─ チェックが入ると value="1" がコントローラーに届く
 * └─ チェックなしの場合は null になる（フォームに含まれないため）
 *
 * array_merge()（アレイマージ）
 * └─ 複数の配列を1つにまとめるPHPの関数
 * └─ 今回は validated() の結果に image_path を追加するために使う
 *
 * nullable（ナラブル）
 * └─ データベースのカラムで「null（空）でもOK」という設定
 * └─ image_path は nullable なので null で保存できる
 *
 * ファサード（Facade）
 * └─ Laravelが用意している「便利な道具箱」へのアクセス方法
 * └─ Storage ファサード = ファイル操作のための道具箱
 * └─ use Illuminate\Support\Facades\Storage; で使えるようになる
 */
