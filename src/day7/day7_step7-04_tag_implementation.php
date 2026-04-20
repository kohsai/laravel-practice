<?php

/**
 * 📘 Day7 教材（Step7-04：実践・タグ機能の実装）
 *
 * この教材では Step7-03 で学んだ「多対多（たたいた）リレーション」の知識を使って、
 * 実際にタグ機能を Laravel で作ります
 *
 * 【KOHさんの環境での前提条件】
 * ✅ Tag モデル（app/Models/Tag.php）：作成済み・expenses()・users() あり
 * ✅ Expense モデル（app/Models/Expense.php）：tags() 追加済み
 * ✅ tags テーブル：作成済み・10件のデータあり（食費・娯楽・節約・外食・日用品・交通費・趣味・医療費・水道光熱費・その他）
 * ✅ expense_tag テーブル：作成済み
 * ✅ TagSeeder：実行済み
 * ✅ tinkerでattach・detach・sync の動作確認済み
 * ❌ expenses/index.blade.php：未作成 → 今回作成
 * ❌ expenses/edit.blade.php：未作成 → 今回作成
 * ✅ expenses/create.blade.php：作成済み → タグのチェックボックスを追加
 * ✅ ExpenseController.php：作成済み → タグ対応に修正
 *
 * 【KOHさんの環境の特記事項】
 * - Expenseテーブルのカラム：category・amount・description・spent_at（titleカラムはない）
 * - store()メソッドは array_merge で user_id を付加する形（そのまま維持）
 * - StoreUserRequest でバリデーション済み
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. ExpenseController を修正する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【修正が必要なメソッド】
 *
 * ① index()  → Eagerロード（with）でタグも一緒に取得する
 * ② create() → 全タグを取得してビューに渡す
 * ③ store()  → 保存後にタグをsync()する
 * ④ edit()   → 全タグ・選択済みタグIDをビューに渡す
 * ⑤ update() → 更新後にタグをsync()する
 *
 * 【use文の追加（必須）】
 *
 * ファイル先頭の use 文に以下を追加してください：
 * use App\Models\Tag;
 *
 * 【修正後の ExpenseController.php 全体】
 *
 * ファイルパス：app/Http/Controllers/ExpenseController.php
 */

/**
 * 以下の内容にファイルを書き換えてください：
 *
 * <?php
 *
 * namespace App\Http\Controllers;
 *
 * use App\Models\Expense;
 * use App\Models\Tag;
 * use App\Http\Requests\StoreUserRequest;
 *
 * class ExpenseController extends Controller
 * {
 *     // 一覧表示
 *     public function index()
 *     {
 *         $expenses = Expense::with('tags')
 *             ->where('user_id', auth()->id())
 *             ->latest('spent_at')
 *             ->get();
 *         return view('expenses.index', compact('expenses'));
 *     }
 *
 *     // 作成フォーム表示
 *     public function create()
 *     {
 *         $tags = Tag::all();
 *         return view('expenses.create', compact('tags'));
 *     }
 *
 *     // 保存処理
 *     public function store(StoreUserRequest $request)
 *     {
 *         $expense = Expense::create(
 *             array_merge($request->validated(), ['user_id' => auth()->id()])
 *         );
 *         $expense->tags()->sync($request->input('tag_ids', []));
 *         return redirect()->route('expenses.index')->with('success', '支出を登録しました');
 *     }
 *
 *     // 詳細表示（未実装）
 *     public function show(string $id)
 *     {
 *         //
 *     }
 *
 *     // 編集フォーム表示
 *     public function edit(Expense $expense)
 *     {
 *         $this->authorize('update', $expense);
 *         $tags = Tag::all();
 *         $selectedTagIds = $expense->tags()->pluck('tags.id')->toArray();
 *         return view('expenses.edit', compact('expense', 'tags', 'selectedTagIds'));
 *     }
 *
 *     // 更新処理
 *     public function update(StoreUserRequest $request, Expense $expense)
 *     {
 *         $this->authorize('update', $expense);
 *         $expense->update($request->validated());
 *         $expense->tags()->sync($request->input('tag_ids', []));
 *         return redirect()->route('expenses.index')->with('success', '支出を更新しました');
 *     }
 *
 *     // 削除処理
 *     public function destroy(Expense $expense)
 *     {
 *         $this->authorize('delete', $expense);
 *         $expense->delete();
 *         return redirect()->route('expenses.index')->with('success', '支出を削除しました');
 *     }
 * }
 */

/**
 * 【各メソッドの変更点と理由】
 *
 * ① index()
 *   変更前：中身が空（//）
 *   変更後：with('tags') でタグを一緒に取得、ログイン中のユーザーの支出だけ表示
 *
 *   Expense::with('tags')（ウィズ）
 *   └─ 「支出を取得するとき、タグも一緒に取得して」という命令
 *   └─ N+1問題（エヌプラスいちもんだい）を防ぐ
 *
 *   ->where('user_id', auth()->id())
 *   └─ 「ログイン中のユーザーの支出だけ」に絞る
 *
 *   ->latest('spent_at')
 *   └─ 「支出日が新しい順」に並べる
 *
 * ② create()
 *   変更前：return view('expenses.create');
 *   変更後：$tags = Tag::all() を追加してビューに渡す
 *   理由：フォームにタグのチェックボックスを表示するため
 *
 * ③ store()
 *   変更前：Expense::create(...); return redirect('/expenses');
 *   変更後：$expense に受け取って sync() を追加、route名でリダイレクト
 *
 *   $expense = Expense::create(...)
 *   └─ 作成した支出を $expense に入れる（sync()で使うため）
 *
 *   $expense->tags()->sync($request->input('tag_ids', []))
 *   └─ フォームで選んだタグIDで同期する
 *   └─ tag_ids が届かないとき（未選択）は空配列にする
 *
 * ④ edit()
 *   変更前：authorize のみ
 *   変更後：$tags と $selectedTagIds を追加
 *
 *   $selectedTagIds = $expense->tags()->pluck('tags.id')->toArray()
 *   └─ この支出についているタグのIDを配列で取得する
 *   └─ 例：[1, 3]（食費・節約がついている場合）
 *   └─ Bladeで「すでにチェックが入っている状態」にするために使う
 *
 * ⑤ update()
 *   変更前：authorize + update のみ
 *   変更後：sync() を追加
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. create.blade.php にタグを追加する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【対象ファイル】
 * resources/views/expenses/create.blade.php
 *
 * 既存の <button type="submit">登録する</button> の直前に
 * 以下のタグのチェックボックスを追加してください：
 *
 * <div>
 *     <label>タグ（複数選択可）</label><br>
 *     @foreach ($tags as $tag)
 *         <label style="margin-right: 12px;">
 *             <input type="checkbox"
 *                    name="tag_ids[]"
 *                    value="{{ $tag->id }}">
 *             {{ $tag->name }}
 *         </label>
 *     @endforeach
 * </div>
 *
 * 【ポイント】
 *
 * name="tag_ids[]"（タグアイディーズ）
 * └─ [] をつけることで「複数の値を配列として送信できる」
 * └─ 複数チェックすると tag_ids=[1, 3] のように届く
 *
 * value="{{ $tag->id }}"
 * └─ チェックしたときに「そのタグのID」を送信する
 *
 * @foreach ($tags as $tag)
 * └─ Controllerから渡された全タグを1つずつ表示する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. index.blade.php を新規作成する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【ファイルパス】
 * resources/views/expenses/index.blade.php
 *
 * 【新規作成する内容】
 *
 * <!DOCTYPE html>
 * <html lang="ja">
 * <head>
 *     <meta charset="UTF-8">
 *     <title>支出一覧</title>
 * </head>
 * <body>
 *     <h1>支出一覧</h1>
 *
 *     <a href="{{ route('expenses.create') }}">新しい支出を追加する</a>
 *
 *     @if (session('success'))
 *         <div style="color: green; border: 1px solid green; padding: 10px; margin: 10px 0;">
 *             {{ session('success') }}
 *         </div>
 *     @endif
 *
 *     @if ($expenses->isEmpty())
 *         <p>支出データがまだありません。</p>
 *     @else
 *         <table border="1" cellpadding="8" cellspacing="0">
 *             <tr>
 *                 <th>日付</th>
 *                 <th>カテゴリ</th>
 *                 <th>金額</th>
 *                 <th>説明</th>
 *                 <th>タグ</th>
 *                 <th>操作</th>
 *             </tr>
 *             @foreach ($expenses as $expense)
 *                 <tr>
 *                     <td>{{ $expense->spent_at }}</td>
 *                     <td>{{ $expense->category }}</td>
 *                     <td>{{ number_format($expense->amount) }}円</td>
 *                     <td>{{ $expense->description }}</td>
 *                     <td>
 *                         @foreach ($expense->tags as $tag)
 *                             <span style="background: #dbeafe; padding: 2px 8px; border-radius: 4px; margin-right: 4px;">
 *                                 {{ $tag->name }}
 *                             </span>
 *                         @endforeach
 *                     </td>
 *                     <td>
 *                         <a href="{{ route('expenses.edit', $expense) }}">編集</a>
 *                         <form method="POST" action="{{ route('expenses.destroy', $expense) }}" style="display:inline;">
 *                             @csrf
 *                             @method('DELETE')
 *                             <button type="submit" onclick="return confirm('削除しますか？')">削除</button>
 *                         </form>
 *                     </td>
 *                 </tr>
 *             @endforeach
 *         </table>
 *     @endif
 * </body>
 * </html>
 *
 * 【ポイント解説】
 *
 * $expenses->isEmpty()（イズエンプティ）
 * └─ 「支出データが0件かどうか」を確認する
 * └─ 0件のときは「まだありません」と表示する
 *
 * number_format($expense->amount)（ナンバーフォーマット）
 * └─ 1000 → 1,000 のようにカンマ区切りで表示する
 *
 * $expense->tags（カッコなし）
 * └─ Laravelが自動でタグを取得してくれる（Eagerロード済みなので高速）
 *
 * @method('DELETE')
 * └─ HTMLフォームはGETとPOSTしか送れないため、DELETEを偽装する
 *
 * session('success')（セッション）
 * └─ store()・update()・destroy()で設定した成功メッセージを表示する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. edit.blade.php を新規作成する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【ファイルパス】
 * resources/views/expenses/edit.blade.php
 *
 * 【新規作成する内容】
 *
 * <!DOCTYPE html>
 * <html lang="ja">
 * <head>
 *     <meta charset="UTF-8">
 *     <title>支出を編集</title>
 * </head>
 * <body>
 *     <h1>支出を編集する</h1>
 *
 *     @if ($errors->any())
 *         <div style="color: red; border: 1px solid red; padding: 10px;">
 *             <ul>
 *                 @foreach ($errors->all() as $error)
 *                     <li>{{ $error }}</li>
 *                 @endforeach
 *             </ul>
 *         </div>
 *     @endif
 *
 *     <form method="POST" action="{{ route('expenses.update', $expense) }}">
 *         @csrf
 *         @method('PUT')
 *
 *         <div>
 *             <label>カテゴリ</label><br>
 *             <input type="text" name="category" value="{{ old('category', $expense->category) }}">
 *             @error('category')
 *                 <span style="color: red;">{{ $message }}</span>
 *             @enderror
 *         </div>
 *
 *         <div>
 *             <label>金額（円）</label><br>
 *             <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}">
 *             @error('amount')
 *                 <span style="color: red;">{{ $message }}</span>
 *             @enderror
 *         </div>
 *
 *         <div>
 *             <label>説明（任意）</label><br>
 *             <textarea name="description">{{ old('description', $expense->description) }}</textarea>
 *             @error('description')
 *                 <span style="color: red;">{{ $message }}</span>
 *             @enderror
 *         </div>
 *
 *         <div>
 *             <label>日付</label><br>
 *             <input type="date" name="spent_at" value="{{ old('spent_at', $expense->spent_at) }}">
 *             @error('spent_at')
 *                 <span style="color: red;">{{ $message }}</span>
 *             @enderror
 *         </div>
 *
 *         <div>
 *             <label>タグ（複数選択可）</label><br>
 *             @foreach ($tags as $tag)
 *                 <label style="margin-right: 12px;">
 *                     <input type="checkbox"
 *                            name="tag_ids[]"
 *                            value="{{ $tag->id }}"
 *                            @checked(in_array($tag->id, $selectedTagIds ?? []))>
 *                     {{ $tag->name }}
 *                 </label>
 *             @endforeach
 *         </div>
 *
 *         <button type="submit">更新する</button>
 *     </form>
 *
 *     <a href="{{ route('expenses.index') }}">一覧に戻る</a>
 * </body>
 * </html>
 *
 * 【create.blade.phpとの違い】
 *
 * old('category', $expense->category)
 * └─ 第2引数に既存の値を入れる
 * └─ バリデーションエラー時は old() の値（入力した値）を表示
 * └─ 初回表示時は $expense->category（DBの値）を表示
 *
 * @checked(in_array($tag->id, $selectedTagIds ?? []))
 * └─ @checked（チェクト）= 条件がtrueのとき checked を自動で出力する
 * └─ in_array（イン・アレイ）= 配列の中にその値が含まれているか確認する
 * └─ $selectedTagIds ?? [] = nullのときは空配列にする
 * └─ つまり「この支出にすでについているタグにはチェックを入れる」
 *
 * @method('PUT')
 * └─ HTMLフォームはPUTを送れないため、POSTで偽装する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. ブラウザで動作確認する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【確認手順】
 *
 * 1. ブラウザで http://localhost/expenses にアクセス
 *    → 支出一覧が表示されること
 *    → 既存の支出にタグが表示されること（tinkerでattachしたもの）
 *
 * 2. 「新しい支出を追加する」をクリック
 *    → タグのチェックボックスが表示されること
 *    → 複数タグを選択して登録できること
 *    → 一覧に戻ったとき、選んだタグが表示されること
 *
 * 3. 「編集」をクリック
 *    → 既存のタグにチェックが入った状態で表示されること
 *    → タグを変更して更新できること
 *    → 一覧に戻ったとき、変更後のタグが表示されること
 *
 * 4. 「削除」をクリック
 *    → 確認ダイアログが表示されること
 *    → OKを押すと支出が削除されること
 *    → expense_tagの関連データも自動で削除されること（cascadeOnDelete）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【作業チェックリスト】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【ExpenseController.php の修正】
 * □ use App\Models\Tag; を追加
 * □ index() を修正（with('tags')・where・latest・get）
 * □ create() を修正（$tags = Tag::all() を追加）
 * □ store() を修正（$expense に受け取り・sync() を追加・route名リダイレクト）
 * □ edit() を修正（$tags・$selectedTagIds を追加）
 * □ update() を修正（sync() を追加）
 *
 * 【Bladeファイルの作成・修正】
 * □ create.blade.php にタグのチェックボックスを追加
 * □ index.blade.php を新規作成
 * □ edit.blade.php を新規作成
 *
 * 【動作確認】
 * □ 一覧表示（http://localhost/expenses）
 * □ 新規作成（タグ選択して登録）
 * □ 編集（既存タグにチェックが入っているか・変更できるか）
 * □ 削除（cascadeOnDeleteで expense_tag も削除されるか）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📖 【用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * with（ウィズ）
 * └─ Eagerロードを指定するメソッド。「〇〇も一緒に取得して」
 * └─ N+1問題を防ぐ
 *
 * sync（シンク）
 * └─ 指定したIDだけに関係を「上書き同期」する
 * └─ 不要なタグは自動削除、必要なタグは自動追加
 *
 * pluck（プラック）
 * └─ 特定の列だけ抜き出す。「もぎ取る」イメージ
 *
 * toArray（トゥーアレイ）
 * └─ CollectionをPHPの配列に変換する
 *
 * @checked（チェクト）
 * └─ Bladeディレクティブ。条件がtrueのとき checked を自動で出力する
 *
 * in_array（イン・アレイ）
 * └─ 配列の中に値が含まれているか確認するPHP関数
 *
 * isEmpty（イズエンプティ）
 * └─ Collectionが空かどうかを確認するメソッド
 *
 * number_format（ナンバーフォーマット）
 * └─ 数値をカンマ区切りで表示するPHP関数
 *
 * session（セッション）
 * └─ 一時的なデータを保存する仕組み。リダイレクト後に1回だけ表示される
 *
 * latest（レイテスト）
 * └─ 「新しい順」に並べる。latest('spent_at')は支出日が新しい順
 *
 * N+1問題（エヌプラスいちもんだい）
 * └─ ループの中で1件ずつDBを取得してしまう非効率な問題
 * └─ with() で解決する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ❓ 【Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. store()でExpense::create()の結果を$expenseに受け取る理由は？
 * A1. sync()を使うためです。
 *     sync()は「支出のインスタンス（実際のデータ）」に対して使う必要があります。
 *     $expense = Expense::create(...) とすることで、
 *     作成した支出を $expense に入れて sync() で使えるようにしています。
 *
 * Q2. $request->input('tag_ids', []) の [] は何ですか？
 * A2. 「tag_ids が届かなかったとき（タグを1つも選ばなかったとき）の初期値」です。
 *     タグを1つも選ばずに送信すると tag_ids はリクエストに含まれません。
 *     その場合 [] （空配列）を使って sync([]) = 全タグを外す、という動作になります。
 *
 * Q3. old('category', $expense->category) の第2引数の意味は？
 * A3. 「バリデーションエラーがないときの初期値」です。
 *     - 初回表示時：$expense->category（DBの値）が入力欄に表示される
 *     - バリデーションエラー後：old()の値（ユーザーが入力した値）が表示される
 *     create.blade.phpでは第2引数が不要（新規なのでDBの値がない）でしたが、
 *     edit.blade.phpでは既存の値を表示したいので第2引数が必要です。
 *
 * Q4. $expense->tags と $expense->tags() の違いは？
 * A4. Bladeでは $expense->tags（カッコなし）を使います。
 *     with('tags')でEagerロード済みなので、追加のSQL実行なしで取得できます。
 *     $expense->tags()（カッコあり）はクエリビルダなので、
 *     ->where()などの条件を追加したいときに使います。
 *
 * Q5. cascadeOnDelete() があると expense_tag のデータも消えますか？
 * A5. はい、自動で消えます。
 *     支出を削除すると expense_tag の関連レコードも自動削除されます。
 *     「親（支出）が消えたら子（中間テーブルの記録）も消える」という設定です。
 *     これにより「支出は消えたのにタグの紐付けだけ残る」というゴミデータを防げます。
 */
