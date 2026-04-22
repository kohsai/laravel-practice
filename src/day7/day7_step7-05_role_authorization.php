<?php

/**
 * 📘 Day7 教材（Step7-05：認可と多対多の組み合わせ - ロールによる権限制御）
 *
 * この教材では「ロール（役職）」という仕組みを使って、
 * ユーザーに権限を柔軟に割り当てる方法を学びます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Day7では「認可（にんか）」について学んできました。
 *
 * 【Step7-01〜04のおさらい】
 *
 * Step7-01：Gate（ゲート）
 *   └─ 「この人はこの操作をしていいか？」をシンプルに確認する仕組み
 *   └─ 例：is_admin が 1 なら管理者として全員の支出を見られる
 *
 * Step7-02：Policy（ポリシー）
 *   └─ 特定のモデル（例：Expense）に対する権限をまとめて管理する仕組み
 *   └─ 例：「自分のExpenseだけ編集・削除できる」
 *
 * Step7-03：多対多（たたいた）リレーション
 *   └─ 両方向で複数持てる関係
 *   └─ 例：支出は複数のタグを持てる、タグは複数の支出に使われる
 *
 * Step7-04：タグ機能の実装
 *   └─ 多対多を実際にLaravelで作った
 *   └─ belongsToMany・sync・中間テーブル（expense_tag）
 *
 * 【今日：Step7-05 ← ここが本来のStep7-04のテーマ】
 *
 * 今日は「認可（Step7-01〜02）」と「多対多（Step7-03〜04）」を
 * 組み合わせた発展的な仕組みを学びます。
 *
 * テーマ：「ロール（役職）による権限制御」
 *
 * 【完成イメージ】
 *
 * 現在の仕組み：
 *   users テーブルの is_admin カラムで管理者かどうかを判断
 *   → is_admin = 1 なら管理者、0 なら一般ユーザー
 *
 * 今日作る仕組み：
 *   roles（ロールズ）テーブルを別途作る
 *   → 「admin（管理者）」「editor（編集者）」など、役職を自由に追加できる
 *   → ユーザーと役職は「多対多」でつなぐ
 *   → 1人のユーザーが複数の役職を持てる
 *
 * なぜ今日これを学ぶのか：
 * ① 認可（Gate・Policy）と多対多をセットで使う実践的な技術だから
 * ② 実際のWebアプリでよく使われる設計だから
 * ③ is_admin だけでは「管理者 or 一般」の2択しかないが、
 *    ロールを使えば権限を細かく設定できるから
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📚 【第1部：概念理解】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 */

/**
 * ✅ 【1-1. ロールとは何か】
 *
 * role（ロール） = 「役割・役職」という意味の英単語
 *
 * 【たとえ話：会社の部署と役職】
 *
 * ある会社に「田中さん」がいます。
 *
 * 田中さんの役職：
 *   - 部長（=全社員の情報を見られる）
 *   - プロジェクトマネージャー（=プロジェクトの予算を承認できる）
 *
 * → 田中さんは「2つの役職」を持っています
 *
 * 鈴木さんの役職：
 *   - 一般社員（=自分の情報だけ見られる）
 *
 * → 鈴木さんは「1つの役職」だけ
 *
 * このように「人」と「役職」の関係は多対多（たたいた）です：
 *   - 1人が複数の役職を持てる
 *   - 1つの役職に複数の人が所属できる
 *
 * 【Laravelのロールシステムに置き換えると】
 *
 *   users テーブル（ユーザー情報）
 *     └─ id=1 山田さん
 *     └─ id=2 鈴木さん
 *
 *   roles テーブル（役職情報）
 *     └─ id=1 admin（管理者）
 *     └─ id=2 editor（編集者）
 *
 *   role_user テーブル（中間テーブル：誰がどの役職を持つか）
 *     └─ user_id=1, role_id=1（山田さんは管理者）
 *     └─ user_id=1, role_id=2（山田さんは編集者でもある）
 *     └─ user_id=2, role_id=2（鈴木さんは編集者のみ）
 *
 * Step7-03・04で学んだ多対多の構造そのものです！
 */

/**
 * ✅ 【1-2. is_admin との違い・なぜロールを使うのか】
 *
 * 【現在の仕組み：is_admin カラム】
 *
 * users テーブルに is_admin（イズアドミン）というカラムがある
 *
 *   is_admin = 1 → 管理者
 *   is_admin = 0 → 一般ユーザー
 *
 * 【メリット】
 *   シンプル。コードが少ない。小さなアプリに最適。
 *
 * 【デメリット】
 *   2択しかない。「管理者」か「一般」か。
 *   「投稿だけ編集できる編集者」「閲覧だけできる読み取り専用」などを表現できない。
 *
 * 【たとえ話：鍵の種類】
 *
 * is_admin方式 = 「スペアキーあり or なし」の2択
 *   →「玄関しか入れない」「全部屋入れる」のどちらか
 *
 * ロール方式 = 部屋ごとのカードキー
 *   →「Aさんは玄関と会議室に入れる」
 *   →「Bさんは玄関と倉庫に入れる」
 *   →「Cさんは全部屋入れる」
 *   → 柔軟に設定できる！
 *
 * 【まとめ：使い分けの基準】
 *
 *   小さなアプリ・2種類の権限で十分 → is_admin で十分
 *   権限の種類が増える・細かく設定したい → ロールシステムを使う
 *
 * 今日は「ロールシステム」の作り方を学びます。
 * ただし、is_admin を完全に置き換えるのではなく、
 * 「ロールで is_admin の代わりをする」という発想で作ります。
 */

/**
 * ✅ 【1-3. Gate・Policy・ロールの関係を整理する】
 *
 * 3つの概念が出てきたので、関係を整理します。
 *
 * 【たとえ話：図書館のセキュリティシステム】
 *
 * ▼ Gate（ゲート） = 入口の警備員
 *
 *   「この人は図書館に入っていいか？」をチェックする
 *   → yes / no の判断をする「門番」
 *   例：「会員証を持っていますか？」
 *
 *   Laravelの実装：
 *     Gate::define('admin', function($user) {
 *         return $user->hasRole('admin');  ← 今日実装する！
 *     });
 *
 * ▼ Policy（ポリシー） = 利用ルールブック
 *
 *   「この本（Expense）に対して、この人は何ができるか？」をまとめた本
 *   → 特定のモデルに対するルールをまとめる
 *   例：「自分が借りた本しか延長できない」「司書だけが本を廃棄できる」
 *
 *   Laravelの実装：ExpensePolicy.php（Step7-02で作成済み）
 *
 *   今日修正するポイント：
 *     update()・delete() に「adminなら何でもできる」を追加する
 *
 * ▼ ロール（役割）= 会員の種類
 *
 *   「この人は『一般会員』か、それとも『司書』か、『理事』か」という分類
 *   → ユーザーにラベルを貼る仕組み
 *
 *   Laravelの実装：roles テーブル + role_user 中間テーブル
 *
 * 【3つの関係まとめ】
 *
 *   ユーザーがページにアクセス
 *       ↓
 *   Gate が「入っていいか？」を確認
 *       ↓（その時に...）
 *   ユーザーが持つ「ロール」を確認
 *       ↓（ロールに応じて...）
 *   Policy が「この操作をしていいか？」を確認
 *       ↓
 *   OK なら操作実行 / NG なら403エラー
 *
 * この流れを今日実装します。
 */

/**
 * ✅ 【1-4. 今日実装するものの全体像】
 *
 * 【作るものリスト】
 *
 * ① roles テーブル（役職情報を保存）
 *
 *   カラム：
 *     id         = 番号
 *     name       = 役職名（例：admin）
 *     created_at = 作成日時
 *     updated_at = 更新日時
 *
 * ② Role モデル
 *
 *   役職を扱うモデル（ひな型）
 *   リレーション：users()（どのユーザーがこの役職を持つか）
 *
 * ③ role_user 中間テーブル（誰がどの役職を持つかを記録）
 *
 *   カラム：
 *     id      = 番号
 *     user_id = ユーザーのID
 *     role_id = 役職のID
 *
 * ④ User モデルの修正
 *
 *   追加するメソッド：
 *     roles()   = 多対多リレーション（ユーザーが持つ役職を取得）
 *     hasRole() = 「この役職を持っているか？」を確認するメソッド
 *
 * ⑤ ExpensePolicy の修正
 *
 *   追加するロジック：
 *     「admin ロールを持つユーザーは、誰の支出でも操作できる」
 *
 * ⑥ ExpenseController の index() 修正
 *
 *   「admin なら全員分の支出を表示、一般ユーザーは自分の分だけ」
 *
 * 【変更しないもの】
 *
 *   Blade ファイル（resources/views/expenses/）
 *   → 今回はロジック（裏側）の修正だけ。画面は変えない。
 *
 * 【確認方法】
 *
 *   tinker（ティンカー）でロールの作成・付与・確認
 *   ブラウザで id=1 と id=2 のユーザーでログインし、動作の違いを確認
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🛠 【第2部：実装】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * ⚠️ 注意：実装を始める前に第1部を十分に読んでください。
 * 「何を作っているのか」を理解しながら進めることが大切です。
 */

/**
 * ✅ 【2-1. Role モデルとマイグレーションを作成する】
 *
 * artisan（アーティザン）コマンドで Role モデルと
 * マイグレーションを同時に作成します。
 *
 * -m オプション＝モデルと同時にマイグレーションも作る
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan make:model Role -m

/**
 * 実行すると2つのファイルが生成されます：
 *   app/Models/Role.php（モデル）
 *   database/migrations/xxxx_xx_xx_create_roles_table.php（マイグレーション）
 *
 * 生成されたら教えてください。マイグレーションの中身を編集します。
 */

/**
 * ✅ 【2-2. roles テーブルのマイグレーションを編集する】
 *
 * 生成されたマイグレーションファイルを開いて、
 * 以下の内容に書き換えてください。
 *
 * ファイルの場所：
 *   database/migrations/xxxx_xx_xx_000000_create_roles_table.php
 *   （xxxx は自動生成された日時が入ります）
 */

// 【書き換える内容】
// up() メソッドの中の Schema::create の部分を以下に変更してください：

// Schema::create('roles', function (Blueprint $table) {
//     $table->id();
//     $table->string('name')->unique();  // 役職名（重複禁止）
//     $table->timestamps();
// });

/**
 * 【解説】
 *
 * $table->id()
 *   = 自動連番のID（1, 2, 3...と自動で振られる）
 *
 * $table->string('name')->unique()
 *   = 役職名を保存するカラム（文字列）
 *   string（ストリング）＝文字列
 *   ->unique()（ユニーク）＝重複を禁止する
 *   → 「admin」という役職が2つ存在しないようにするため
 *
 * $table->timestamps()
 *   = created_at と updated_at を自動で追加
 *
 * 書き換えたら教えてください。次に中間テーブルを作ります。
 */

/**
 * ✅ 【2-3. role_user 中間テーブルのマイグレーションを作成する】
 *
 * 【重要：中間テーブルの命名規則（なんでこのファイル名？）】
 *
 * Laravelには「中間テーブルの名前の付け方ルール」があります。
 *
 * ルール：
 *   関連する2つのモデル名を「アルファベット順」で並べて、
 *   アンダースコア（_）でつなぐ
 *
 * 今回の場合：
 *   Role（ロール）と User（ユーザー）を組み合わせる
 *   r < u なので role_user の順番
 *   → テーブル名：role_user
 *
 * 【注意】
 *   これは「roles_users」ではありません！
 *   Laravelは「単数形（ひとつの意味）」で作ります。
 *   roles でも users でもなく、role_user です。
 *
 * 今回は「モデルなし・マイグレーションだけ」作ります。
 * 中間テーブルは「2つのテーブルをつなぐだけ」の役割なので、
 * 通常はモデルを作りません。
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan make:migration create_role_user_table

/**
 * ✅ 【2-4. role_user テーブルのマイグレーションを編集する】
 *
 * 生成されたファイルを開いて、以下の内容に書き換えてください：
 */

// Schema::create('role_user', function (Blueprint $table) {
//     $table->id();
//     $table->foreignId('role_id')->constrained()->onDelete('cascade');
//     $table->foreignId('user_id')->constrained()->onDelete('cascade');
//     $table->timestamps();
// });

/**
 * 【解説】
 *
 * $table->foreignId('role_id')->constrained()->onDelete('cascade')
 *   foreignId（フォーリンアイディ）＝外部キー（別テーブルのIDを参照する列）
 *   'role_id' = roles テーブルの id を参照する列
 *   ->constrained()（コンストレインド）= 「roles テーブルに存在するIDだけ許可」という制約
 *   ->onDelete('cascade')（オンデリート・カスケード）
 *     = 「ロールが削除されたら、その関係レコードも自動削除」
 *
 * $table->foreignId('user_id')->constrained()->onDelete('cascade')
 *   = users テーブルの id を参照する列
 *   = ユーザーが削除されたら、その人のロール関係も自動削除
 *
 * 【たとえ話：カスケードとは？】
 *
 * カスケード = 「滝のように連鎖する」という意味
 *
 * 例：「admin」というロールを削除したとき
 *   → role_user テーブルの「role_id=1（admin）」という行も自動で削除される
 *   → 孤立したデータが残らない
 *
 * 書き換えたら教えてください。マイグレーションを実行します。
 */

/**
 * ✅ 【2-5. マイグレーションを実行する】
 *
 * 2つのマイグレーション（roles・role_user）をデータベースに反映します。
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan migrate

/**
 * 実行後、phpMyAdmin で以下を確認してください：
 *   - roles テーブルが作成されている
 *   - role_user テーブルが作成されている
 *
 * 確認できたら教えてください。次にモデルを編集します。
 */

/**
 * ✅ 【2-6. Role モデルにリレーションを定義する】
 *
 * app/Models/Role.php を開いて、以下の内容に書き換えてください。
 */

// class Role extends Model
// {
//     use HasFactory;
//
//     protected $fillable = ['name'];
//
//     /**
//      * このロールを持つユーザーを取得する
//      * 多対多（たたいた）リレーション
//      */
//     public function users()
//     {
//         return $this->belongsToMany(User::class);
//     }
// }

/**
 * 【解説】
 *
 * protected $fillable = ['name']
 *   = 「name カラムだけ一括登録を許可する」という設定
 *   fillable（フィラブル）= 埋められる = 一括登録できる
 *
 * public function users()
 *   = 「このロールを持つユーザーを取得する」メソッド
 *
 * return $this->belongsToMany(User::class)
 *   = 多対多（たたいた）リレーション
 *   belongsToMany（ビロングズトゥメニー）= 多対多
 *   User::class = User モデルを参照する
 *   中間テーブル名（role_user）は Laravel が自動で判断してくれる
 */

/**
 * ✅ 【2-7. User モデルに roles() と hasRole() を追加する】
 *
 * app/Models/User.php を開いて、既存のメソッドの下に以下を追加してください。
 * （ファイルを全部書き換えるのではなく、追加です）
 */

// /**
//  * このユーザーが持つロールを取得する
//  * 多対多（たたいた）リレーション
//  */
// public function roles()
// {
//     return $this->belongsToMany(Role::class);
// }
//
// /**
//  * 指定したロールを持っているか確認する
//  *
//  * 使い方：$user->hasRole('admin')
//  * → admin ロールを持っていれば true、なければ false
//  */
// public function hasRole(string $roleName): bool
// {
//     return $this->roles->contains('name', $roleName);
// }

/**
 * 【解説：hasRole メソッド（ハズロール）】
 *
 * public function hasRole(string $roleName): bool
 *   string（ストリング）= 文字列を受け取る
 *   $roleName = 確認したい役職名（例：'admin'）
 *   : bool = 返ってくるのは true か false
 *
 * return $this->roles->contains('name', $roleName)
 *   $this->roles = このユーザーが持つロールの一覧
 *   ->contains('name', $roleName) = 「name が $roleName と一致するものが含まれるか？」
 *   contains（コンテインズ）= 含まれているか？
 *
 * 【使い方の例】
 *
 *   $user->hasRole('admin')  → admin ロールがあれば true
 *   $user->hasRole('editor') → editor ロールがあれば true
 *   $user->hasRole('xxx')    → 存在しないロールなら false
 *
 * 【注意：User.php の先頭に use 文を追加】
 *
 * User.php の先頭に Role モデルの参照を追加する必要があります。
 * 既存の use 文の下に追加してください：
 *
 * use App\Models\Role;
 *
 * 場所の目安：`use Illuminate\Foundation\Auth\User as Authenticatable;` の下あたり
 */

/**
 * ✅ 【2-8. tinker でロールを作成・付与・動作確認する】
 *
 * モデルができたので、実際にロールを作って、ユーザーに付与します。
 */

// tinker を起動してください：
// docker compose exec php php artisan tinker

/**
 * 起動したら、以下を順番に実行してください。
 *
 * 【手順1：admin ロールを作成する】
 */

// 以下のコードをコピーしてtinkerで実行してください：
Role::create(['name' => 'admin']);

/**
 * → admin ロールが作成されます（id=1 になります）
 *
 * 【手順2：editor ロールを作成する（発展用・今回は使いませんが練習として）】
 */

// 以下のコードをコピーしてtinkerで実行してください：
Role::create(['name' => 'editor']);

/**
 * → editor ロールが作成されます（id=2 になります）
 *
 * 【手順3：id=1 のユーザー（test@example.com）に admin ロールを付与する】
 */

// 以下のコードをコピーしてtinkerで実行してください：
$user = User::find(1);
$user->roles()->attach(1);

/**
 * → id=1 のユーザーに admin ロール（id=1）を付与しました
 *
 * attach（アタッチ）= 関係を追加する（Step7-03で学んだ）
 *
 * 【手順4：付与されているか確認する】
 */

// 以下のコードをコピーしてtinkerで実行してください：
$user = User::find(1);
$user->roles;

/**
 * → id=1 のユーザーが持つロールの一覧が表示されます
 *   [{"id":1,"name":"admin",...}] のように表示されれば成功
 *
 * 【手順5：hasRole() が正しく動くか確認する】
 */

// 以下のコードをコピーしてtinkerで実行してください：
$user = User::find(1);
$user->hasRole('admin');

/**
 * → true が返ってくれば成功！
 *
 * 続けて確認：
 */

// 以下のコードをコピーしてtinkerで実行してください：
$user2 = User::find(2);
$user2->hasRole('admin');

/**
 * → false が返ってくれば成功！
 *   id=2 のユーザー（kosai@mail.com）は admin ロールを持っていないので false
 *
 * すべて確認できたら tinker を終了してください：
 * exit
 */

/**
 * ✅ 【2-9. ExpensePolicy を修正する】
 *
 * 現在の ExpensePolicy（エクスペンス・ポリシー）は：
 *   「自分の Expense だけ update・delete できる」
 *
 * これを修正して：
 *   「admin ロールを持つユーザーは、誰の Expense でも update・delete できる」
 *
 * app/Policies/ExpensePolicy.php を開いてください。
 * update() と delete() メソッドを以下のように修正します。
 */

// 【update() の修正前】
// public function update(User $user, Expense $expense): bool
// {
//     return $user->id === $expense->user_id;
// }

// 【update() の修正後】
// public function update(User $user, Expense $expense): bool
// {
//     // admin ロールを持つユーザーは誰の Expense でも編集できる
//     if ($user->hasRole('admin')) {
//         return true;
//     }
//     // それ以外は自分の Expense だけ編集できる
//     return $user->id === $expense->user_id;
// }

// 【delete() も同じように修正する】
// public function delete(User $user, Expense $expense): bool
// {
//     // admin ロールを持つユーザーは誰の Expense でも削除できる
//     if ($user->hasRole('admin')) {
//         return true;
//     }
//     // それ以外は自分の Expense だけ削除できる
//     return $user->id === $expense->user_id;
// }

/**
 * 【解説】
 *
 * if ($user->hasRole('admin')) { return true; }
 *   = 「admin ロールを持っているなら、問答無用で true（許可）」
 *
 * return $user->id === $expense->user_id;
 *   = 「admin でなければ、自分の Expense かどうかで判断」
 *
 * 修正できたら教えてください。次は Controller を修正します。
 */

/**
 * ✅ 【2-10. ExpenseController の index() を修正する】
 *
 * 現在の index() メソッドは全員の支出を表示（または is_admin で分岐）していますが、
 * ロールを使った判断に切り替えます。
 *
 * app/Http/Controllers/ExpenseController.php を開いて、
 * index() メソッドを以下のように修正してください。
 */

// public function index()
// {
//     $user = auth()->user();
//
//     // admin ロールを持つユーザーは全員分の支出を表示
//     if ($user->hasRole('admin')) {
//         $expenses = Expense::with('tags')->latest()->get();
//     } else {
//         // 一般ユーザーは自分の支出だけ表示
//         $expenses = Expense::with('tags')
//             ->where('user_id', $user->id)
//             ->latest()
//             ->get();
//     }
//
//     return view('expenses.index', compact('expenses'));
// }

/**
 * 【解説】
 *
 * $user = auth()->user()
 *   = 現在ログインしているユーザーを取得する
 *   auth()（オース）= 認証（ログイン情報）を扱うヘルパー関数
 *
 * if ($user->hasRole('admin'))
 *   = 「admin ロールを持っているか？」を確認
 *
 * Expense::with('tags')->latest()->get()
 *   = 全員分の支出を、タグ情報付きで、新しい順に取得
 *   with('tags') = タグも一緒に取得（N+1問題防止）
 *   latest() = 新しい順（created_at の降順）
 *   get() = 実際に取得する
 *
 * Expense::with('tags')->where('user_id', $user->id)->latest()->get()
 *   = 自分の支出だけをタグ付きで新しい順に取得
 *   where('user_id', $user->id) = 自分のIDと一致する支出だけ
 *
 * 修正できたら教えてください。ブラウザで動作確認します。
 */

/**
 * ✅ 【2-11. ブラウザで動作確認する】
 *
 * 2つのアカウントでログインして、表示の違いを確認します。
 *
 * 【確認1：admin ロールを持つユーザー（id=1）でログイン】
 *
 * ① ブラウザで http://localhost:8080 にアクセス
 * ② test@example.com / password123 でログイン
 * ③ 支出一覧ページを表示
 * → 全員分の支出が表示されれば成功！
 *
 * 【確認2：一般ユーザー（id=2）でログイン】
 *
 * ① 一度ログアウト
 * ② kosai@mail.com / password123 でログイン
 * ③ 支出一覧ページを表示
 * → 自分（id=2）の支出だけが表示されれば成功！
 *
 * 【確認3：edit/delete の権限確認（任意）】
 *
 * id=2 でログインした状態で、id=1 が作成した支出の編集ページに
 * 直接アクセスしてみてください（URLを手入力）。
 * → 403 エラー（Forbidden = 禁止）が表示されれば成功！
 *
 * 結果を教えてください。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📖 【第3部：まとめ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 */

/**
 * ✅ 【3-1. Gate・Policy・ロールの使い分け整理】
 *
 * Day7 で学んだ3つの概念の使い分けをまとめます。
 *
 * ┌─────────────────────────────────────────────┐
 * │  使う場面            → 使うもの             │
 * ├─────────────────────────────────────────────┤
 * │ 単純な yes/no の判断  → Gate（ゲート）       │
 * │ 特定モデルへの権限    → Policy（ポリシー）   │
 * │ ユーザーへの役職付与  → ロール（Role）       │
 * └─────────────────────────────────────────────┘
 *
 * 【実際のWebアプリでの使い方例】
 *
 * Gate の例：
 *   「管理者メニューページへのアクセス許可」
 *   → ページに入れるかどうかの判断
 *
 * Policy の例：
 *   「この支出（Expense）を編集・削除できるか」
 *   → 特定のデータに対する操作権限
 *
 * ロールの例：
 *   「このユーザーは admin か？ editor か？ viewer か？」
 *   → ユーザーに役割を割り当て、Gate や Policy の判断材料にする
 *
 * 【今日の実装での連携】
 *
 *   User.hasRole('admin')   ← ロールで「admin かどうか」を確認
 *       ↓
 *   ExpensePolicy.update()  ← ロールの結果を使って判断
 *   ExpenseController.index() ← ロールの結果に応じてデータを変える
 *
 * 3つの概念が連携して動いています！
 */

/**
 * ✅ 【3-2. 用語集】
 *
 * role（ロール）
 *   └─ 役割・役職のこと
 *   └─ 例：admin（管理者）、editor（編集者）、viewer（閲覧者）
 *
 * roles テーブル
 *   └─ 役職情報を保存するテーブル
 *   └─ カラム：id, name
 *
 * role_user テーブル（中間テーブル）
 *   └─ 誰がどの役職を持つかを記録する
 *   └─ カラム：id, role_id, user_id
 *
 * belongsToMany（ビロングズトゥメニー）
 *   └─ 多対多リレーションを定義するメソッド
 *   └─ Step7-03・04でも使った
 *
 * hasRole()（ハズロール）
 *   └─ 「指定した役職を持っているか？」を確認するメソッド
 *   └─ 返ってくるのは true か false
 *
 * contains()（コンテインズ）
 *   └─ 「含まれているか？」を確認するコレクションのメソッド
 *   └─ hasRole() の内部で使っている
 *
 * fillable（フィラブル）
 *   └─ 一括登録（create()）を許可するカラム名のリスト
 *   └─ セキュリティ上、許可するカラムを明示的に指定する
 *
 * constrained()（コンストレインド）
 *   └─ 「参照するテーブルに存在するIDだけ許可」という制約
 *   └─ 例：role_id に存在しない ID を入れようとするとエラーになる
 *
 * cascade（カスケード）
 *   └─ 「滝のように連鎖する」という意味
 *   └─ onDelete('cascade') = 親が削除されたら子も自動削除
 *
 * unique()（ユニーク）
 *   └─ 重複を禁止する
 *   └─ $table->string('name')->unique() = name に同じ値は入れられない
 */

/**
 * ✅ 【3-3. Q&A】
 *
 * Q1. なぜ中間テーブルの名前は role_user なのですか？
 *     「roles_users」ではないのですか？
 *
 * A1. Laravel には「中間テーブルの命名規則」があります。
 *     2つのモデル名を「単数形・アルファベット順」でアンダースコアでつなぎます。
 *
 *     Role と User → r < u なので role_user（単数形）
 *
 *     Laravel はこのルールを知っているので、
 *     belongsToMany() に中間テーブル名を書かなくても自動で判断してくれます。
 *
 * ---
 *
 * Q2. attach() と sync() はどう違いますか？
 *
 * A2. 使い目的が違います。
 *
 *     attach（アタッチ）= 「追加する」
 *       → 今ある関係はそのままで、新しい関係を追加
 *       → 例：admin ロールを持っているユーザーに editor も追加
 *
 *     sync（シンク）= 「上書きする」
 *       → 指定したものだけに置き換える
 *       → 例：sync([1]) にすると、admin（id=1）だけの状態になる
 *                他のロールはすべて削除される
 *
 *     ロールの「追加」なら attach()
 *     ロールの「置き換え」なら sync()
 *
 * ---
 *
 * Q3. is_admin カラムはこれからも使いますか？
 *
 * A3. 今日の実装では、is_admin の代わりに hasRole('admin') を使うようにしました。
 *
 *     ただし、今回は is_admin カラムを削除していません。
 *     既存のコードに is_admin を使っている箇所がある場合、
 *     それを全部 hasRole() に置き換える必要があります。
 *
 *     今回の学習の範囲では「ロールで権限を判断する方法を理解する」が目的なので、
 *     is_admin の完全置き換えはしていません。
 *
 * ---
 *
 * Q4. roles テーブルのデータはシーダー（Seeder）で作れますか？
 *
 * A4. はい、作れます。今日は tinker で手動作成しましたが、
 *     実際のアプリでは Seeder を使って自動で初期データを入れることが多いです。
 *
 *     Seeder（シーダー）= データベースに初期データを入れる仕組み
 *
 *     今回は tinker での確認を優先しました。
 *     Seeder については Day10（テスト・本番環境準備）で学びます。
 *
 * ---
 *
 * Q5. 今日の内容は「実際のLaravelアプリ」で使われますか？
 *
 * A5. はい、よく使われます。
 *
 *     今日作ったような「ロールによる権限制御」の仕組みを
 *     「RBAC（アールバック）：Role-Based Access Control（ロールベースのアクセス制御）」
 *     と呼びます。
 *
 *     多くのWebアプリで使われる標準的な設計です。
 *
 *     ただし、より複雑な権限管理が必要になった場合は、
 *     Laravel-permission などの外部パッケージを使うことも多いです。
 *     今日はその土台となる考え方を自力で実装しました。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🎯 【Day7 全体のまとめ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Day7では以下を学びました：
 *
 * Step7-01：Gate（ゲート）
 *   → yes/no のシンプルな認可の仕組み
 *
 * Step7-02：Policy（ポリシー）
 *   → 特定モデルに対する権限のルールブック
 *
 * Step7-03：多対多（たたいた）リレーション
 *   → 両方向で複数持てる関係・中間テーブルの作り方
 *
 * Step7-04：タグ機能の実装
 *   → 多対多を使った実際の機能開発
 *
 * Step7-05：認可と多対多の組み合わせ（←今日）
 *   → ロールシステムで柔軟な権限制御を実現
 *
 * 【重要な気づき】
 *
 * Gate・Policy・ロールは「それぞれ別々の機能」ではなく、
 * 連携して動くことで強力な権限制御ができます。
 *
 * 「ロールで役職を管理」→「Gate・Policyがロールを使って判断」
 *
 * この連携パターンは実際のWebアプリで頻繁に使われます。
 *
 * お疲れ様でした！Day7完了です🎉
 */
