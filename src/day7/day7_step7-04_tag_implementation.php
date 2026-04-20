<?php

/**
 * 📘 Day7 教材（Step7-04：実践・タグ機能の実装）統合版
 *
 * この教材では Step7-03 で学んだ「多対多（たたいた）リレーション」の知識を使って、
 * 実際にタグ機能を Laravel で作ります
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📋 【Step7-04の経緯】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step7-04の当初のテーマは「認可と多対多の組み合わせ（応用）」でしたが、
 * 教材作成時にKlogeがテーマを無断変更し「タグ機能の実装」になりました。
 * 「認可と多対多の組み合わせ」はStep7-05として別途実施することになりました。
 *
 * また、Step7-04の教材作成前に、前のチャットですでに以下の作業が完了していました：
 *
 * ✅ expense_tag 中間テーブルの作成・マイグレーション実行
 * ✅ Expense.php に tags() メソッドを追加
 * ✅ Tag.php に expenses() メソッドを追加
 * ✅ TagSeeder の作成・実行（10件のタグ登録）
 * ✅ tinkerで attach・detach・sync の動作確認
 *
 * 上記の作業はv1教材に基づいて行われましたが、教材と実際の環境の差異があったため、
 * v2教材に作り直し、v2教材に基づいて以下の作業を実施しました。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step7-03で「多対多リレーション」の概念・attach/detach/syncを学びました。
 * このStepでは「Expense（支出）にタグをつける機能」を実際に作ります。
 *
 * 【完成イメージ】
 * - 「食費」「外食」「趣味」などのタグを支出に複数つけられる
 * - 支出登録・編集フォームにチェックボックスでタグを選べる
 * - 支出一覧でタグが表示される
 *
 * 【前提となる環境（Step7-04開始時点）】
 *
 * ✅ Tag モデル（app/Models/Tag.php）：作成済み・expenses()・users() あり
 * ✅ Expense モデル（app/Models/Expense.php）：tags() 追加済み
 * ✅ tags テーブル：作成済み・10件のデータあり
 *    （食費・娯楽・節約・外食・日用品・交通費・趣味・医療費・水道光熱費・その他）
 * ✅ expense_tag テーブル：作成済み
 * ✅ TagSeeder：実行済み
 * ✅ tinkerでattach・detach・sync の動作確認済み
 * ❌ expenses/index.blade.php：未作成 → 今回作成
 * ❌ expenses/edit.blade.php：未作成 → 今回作成
 * ✅ expenses/create.blade.php：作成済み → タグのチェックボックスを追加
 * ✅ ExpenseController.php：作成済み → タグ対応に修正
 *
 * 【Expenseテーブルのカラム構成】
 * category・amount・description・spent_at（titleカラムはない）
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. ExpenseController を修正する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【修正が必要なメソッドと理由】
 *
 * ① index()
 *    → Eagerロード（with）でタグも一緒に取得する
 *    → with('tags') がないと、一覧表示のたびにタグ取得のSQLが何度も実行されてしまう
 *       （N+1問題（エヌプラスいちもんだい））
 *
 * ② create()
 *    → 全タグを取得してビューに渡す
 *    → フォームにタグのチェックボックスを表示するため
 *
 * ③ store()
 *    → $expense に受け取って sync() を追加
 *    → Expense::create() の戻り値を $expense に入れないと sync() で使えない
 *
 * ④ edit()
 *    → $tags と $selectedTagIds を追加
 *    → $selectedTagIds = すでについているタグのID配列（チェック済み状態を再現するため）
 *
 * ⑤ update()
 *    → sync() を追加
 *    → 更新時にタグの紐付けも一緒に上書きする
 *
 * 【修正後の ExpenseController.php 全体】
 */

// 以下が修正後の完成形です：

/**
 * <?php
 *
 * namespace App\Http\Controllers;
 *
 * use App\Models\Tag;
 * use App\Models\Expense;
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
 * 【各メソッドのポイント解説】
 *
 * Expense::with('tags')（ウィズ）
 * └─ 「支出を取得するとき、タグも一緒に取得して」という命令
 * └─ N+1問題を防ぐ（SQLの無駄な実行を避ける）
 *
 * ->where('user_id', auth()->id())
 * └─ 「ログイン中のユーザーの支出だけ」に絞る
 *
 * ->latest('spent_at')
 * └─ 「支出日が新しい順」に並べる
 *
 * $expense = Expense::create(...)
 * └─ 作成した支出を $expense に入れる（sync()で使うため）
 * └─ $expense = がないと、sync()で使う「支出のインスタンス」がない
 *
 * $expense->tags()->sync($request->input('tag_ids', []))
 * └─ フォームで選んだタグIDで同期する
 * └─ tag_ids が届かないとき（未選択）は空配列 [] にする
 *
 * $selectedTagIds = $expense->tags()->pluck('tags.id')->toArray()
 * └─ この支出についているタグのIDを配列で取得する
 * └─ 例：[1, 3]（食費・節約がついている場合）
 * └─ Bladeで「すでにチェックが入っている状態」にするために使う
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. create.blade.php にタグを追加する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 既存の <button type="submit">登録する</button> の直前に
 * タグのチェックボックスを追加します。
 *
 * 【追加するコード】
 *
 * <div>
 *     <label>タグ（複数選択可）</label><br>
 *     @foreach ($tags as $tag)
 *         <label style="margin-right: 12px;">
 *             <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}">
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
 * ファイルパス：resources/views/expenses/index.blade.php
 *
 * 【ポイント解説】
 *
 * $expenses->isEmpty()（イズエンプティ）
 * └─ 「支出データが0件かどうか」を確認する
 *
 * number_format($expense->amount)（ナンバーフォーマット）
 * └─ 1000 → 1,000 のようにカンマ区切りで表示する
 *
 * $expense->tags（カッコなし）
 * └─ with('tags')でEagerロード済みなので追加のSQL実行なしで取得できる
 *
 * @method('DELETE')
 * └─ HTMLフォームはGETとPOSTしか送れないため、DELETEを偽装する
 *
 * session('success')（セッション）
 * └─ store()・update()・destroy()で設定した成功メッセージを表示する
 *    リダイレクト後に1回だけ表示される
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. edit.blade.php を新規作成する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * ファイルパス：resources/views/expenses/edit.blade.php
 *
 * 【create.blade.phpとの主な違い】
 *
 * old('category', $expense->category)
 * └─ 第2引数に既存の値を入れる
 * └─ 初回表示時：$expense->category（DBの値）を表示
 * └─ バリデーションエラー後：old()の値（入力した値）を表示
 *
 * @checked(in_array($tag->id, $selectedTagIds ?? []))
 * └─ @checked（チェクト）= 条件がtrueのとき checked を自動で出力する
 * └─ in_array（イン・アレイ）= 配列の中にその値が含まれているか確認する
 * └─ $selectedTagIds ?? [] = nullのときは空配列にする
 * └─ 「この支出にすでについているタグにはチェックを入れる」
 *
 * @method('PUT')
 * └─ HTMLフォームはPUTを送れないため、POSTで偽装する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. 動作確認結果】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * ✅ http://localhost:8080/expenses → 支出一覧が表示された
 * ✅ 「新しい支出を追加する」→ タグのチェックボックスが10件表示された
 * ✅ 支出を登録 → 「支出を登録しました」メッセージが表示された
 * ✅ 「編集」→ 既存のタグにチェックが入った状態で表示された
 * ✅ タグを変更して更新 → 「支出を更新しました」、一覧にタグが表示された
 * ✅ cascadeOnDelete によりexpense_tagの関連データも自動削除される
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
 * N+1問題（エヌプラスいちもんだい）
 * └─ ループの中で1件ずつDBを取得してしまう非効率な問題
 * └─ 例：支出が10件あるとき、タグを1件ずつ取得すると計11回SQLが実行される
 * └─ with() を使うと2回のSQLで済む
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
 * └─ 「新しい順」に並べる
 *
 * cascadeOnDelete（カスケードオンデリート）
 * └─ 親（支出）が削除されたとき、子（中間テーブルの記録）も自動削除する設定
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ❓ 【Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. store()で Expense::create() の結果を $expense に受け取る理由は？
 * A1. sync() を使うためです。
 *     $expense = Expense::create(...) とすることで、
 *     作成した支出を $expense に入れて sync() で使えるようにしています。
 *     $expense = がないと「どの支出にタグをつけるか」が分からずエラーになります。
 *
 * Q2. $request->input('tag_ids', []) の [] は何ですか？
 * A2. タグを1つも選ばなかったときの初期値です。
 *     タグ未選択で送信すると tag_ids はリクエストに含まれません。
 *     その場合 [] （空配列）を使って sync([]) = 全タグを外す、という動作になります。
 *
 * Q3. old('category', $expense->category) の第2引数の意味は？
 * A3. バリデーションエラーがないときの初期値です。
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
