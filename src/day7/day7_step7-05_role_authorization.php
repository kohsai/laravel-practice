<?php

/**
 * 📘 Day7 教材（Step7-05：認可と多対多の組み合わせ - ロールによる権限制御）
 * 統合版（基本教材 + 学習中の気づき・Q&A）
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
 *   is_admin = 1 → 管理者
 *   is_admin = 0 → 一般ユーザー
 *
 * 【メリット】シンプル。コードが少ない。小さなアプリに最適。
 *
 * 【デメリット】2択しかない。
 *   「投稿だけ編集できる編集者」「閲覧だけできる読み取り専用」などを表現できない。
 *
 * 【たとえ話：鍵の種類】
 *
 * is_admin方式 = 「スペアキーあり or なし」の2択
 *
 * ロール方式 = 部屋ごとのカードキー
 *   →「Aさんは玄関と会議室に入れる」
 *   →「Bさんは玄関と倉庫に入れる」
 *   →「Cさんは全部屋入れる」
 *
 * 【まとめ：使い分けの基準】
 *   小さなアプリ・2種類の権限で十分 → is_admin で十分
 *   権限の種類が増える・細かく設定したい → ロールシステムを使う
 */

/**
 * ✅ 【1-3. Gate・Policy・ロールの関係を整理する】
 *
 * 【たとえ話：図書館のセキュリティシステム】
 *
 * ▼ Gate（ゲート） = 入口の警備員
 *   「この人は図書館に入っていいか？」→ yes / no の判断
 *
 * ▼ Policy（ポリシー） = 利用ルールブック
 *   「この本（Expense）に対して、この人は何ができるか？」
 *   → 特定のモデルに対するルールをまとめる
 *
 * ▼ ロール（役割）= 会員の種類
 *   「この人は『一般会員』か、それとも『司書』か、『理事』か」
 *   → ユーザーにラベルを貼る仕組み
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
 */

/**
 * ✅ 【1-4. 今日実装するものの全体像】
 *
 * ① roles テーブル（役職情報を保存）
 *   カラム：id, name, created_at, updated_at
 *
 * ② Role モデル
 *   リレーション：users()
 *
 * ③ role_user 中間テーブル
 *   カラム：id, role_id, user_id
 *
 * ④ User モデルの修正
 *   追加：roles()・hasRole()
 *
 * ⑤ ExpensePolicy の修正
 *   「admin ロールを持つユーザーは、誰の支出でも操作できる」
 *
 * ⑥ ExpenseController の index() 修正
 *   「admin なら全員分、一般ユーザーは自分の分だけ」
 *
 * 【変更しないもの】
 *   Blade ファイル → 今回はロジック（裏側）の修正だけ
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🛠 【第2部：実装】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 */

/**
 * ✅ 【2-1. Role モデルとマイグレーションを作成する】
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan make:model Role -m

/**
 * 実行すると2つのファイルが生成されます：
 *   app/Models/Role.php
 *   database/migrations/xxxx_xx_xx_create_roles_table.php
 *
 * 【注意】生成直後はデフォルトの状態（name カラムなし）です。
 * 次の2-2で編集します。
 */

/**
 * ✅ 【2-2. roles テーブルのマイグレーションを編集する】
 */

// Schema::create('roles', function (Blueprint $table) {
//     $table->id();
//     $table->string('name')->unique();  // 役職名（重複禁止）
//     $table->timestamps();
// });

/**
 * 【解説】
 * ->unique()（ユニーク）＝重複を禁止する
 * → 「admin」という役職が2つ存在しないようにするため
 */

/**
 * ✅ 【2-3. role_user 中間テーブルのマイグレーションを作成する】
 *
 * 【重要：中間テーブルの命名規則】
 *
 * ルール：2つのモデル名を「単数形・アルファベット順」でアンダースコアでつなぐ
 *
 * Role と User → r < u なので role_user（単数形）
 * 「roles_users」ではありません！
 *
 * 中間テーブルは通常モデルを作りません。マイグレーションだけ作ります。
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan make:migration create_role_user_table

/**
 * ✅ 【2-4. role_user テーブルのマイグレーションを編集する】
 */

// Schema::create('role_user', function (Blueprint $table) {
//     $table->id();
//     $table->foreignId('role_id')->constrained()->onDelete('cascade');
//     $table->foreignId('user_id')->constrained()->onDelete('cascade');
//     $table->timestamps();
// });

/**
 * 【解説】
 * foreignId（フォーリンアイディ）＝外部キー
 * ->constrained()（コンストレインド）= 参照するテーブルに存在するIDだけ許可
 * ->onDelete('cascade')（カスケード）= 親が削除されたら子も自動削除
 *
 * 【たとえ話：カスケードとは？】
 * 「admin」ロールを削除したとき
 * → role_user の「role_id=1」の行も自動で削除される
 * → 孤立したデータが残らない
 */

/**
 * ✅ 【2-5. マイグレーションを実行する】
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan migrate

/**
 * phpMyAdmin で roles と role_user の2つのテーブルを確認してください。
 */

/**
 * ✅ 【2-6. Role モデルにリレーションを定義する】
 */

// class Role extends Model
// {
//     use HasFactory;
//
//     protected $fillable = ['name'];
//
//     public function users()
//     {
//         return $this->belongsToMany(User::class);
//     }
// }

/**
 * 【解説】
 * fillable（フィラブル）= 一括登録（create()）を許可するカラム名のリスト
 * belongsToMany = 多対多リレーション
 * 中間テーブル名（role_user）は Laravel が自動で判断してくれる
 */

/**
 * ✅ 【2-7. User モデルに roles() と hasRole() を追加する】
 *
 * 先頭の use 文に `use App\Models\Role;` を追加してから、
 * 以下のメソッドを追加してください。
 */

// public function roles()
// {
//     return $this->belongsToMany(Role::class);
// }
//
// public function hasRole(string $roleName): bool
// {
//     return $this->roles->contains('name', $roleName);
// }

/**
 * 【解説：hasRole メソッド（ハズロール）】
 *
 * $this->roles->contains('name', $roleName)
 *   $this->roles = このユーザーが持つロールの一覧
 *   ->contains（コンテインズ）= 含まれているか？
 *
 * 使い方：
 *   $user->hasRole('admin')  → true / false が返ってくる
 */

/**
 * ✅ 【2-8. tinker でロールを作成・付与・動作確認する】
 */

// docker compose exec php php artisan tinker

/**
 * 【重要：tinker でのカスタムモデルの使い方】
 *
 * tinker 内でカスタムモデル（Role など）を使う場合、
 * use 文か完全修飾名が必要です。
 *
 * 方法1：use 文を先に宣言する（推奨）
 *   use App\Models\Role;
 *   Role::create(['name' => 'admin']);
 *
 * 方法2：完全修飾名で書く
 *   App\Models\Role::create(['name' => 'admin']);
 *
 * 【学習中の気づき】
 * Step7-04 の tinker 作業でも Class "Tag" not found. が発生していました。
 * その時は use App\Models\Tag; で解決しています。
 * 同じ原因・同じ解決方法です。
 * tinker でカスタムモデルを使う時は use 文を先に宣言する習慣をつけましょう。
 *
 * 【手順1：admin ロールを作成する】
 */

// 以下のコードをコピーしてtinkerで実行してください：
use App\Models\Role;

Role::create(['name' => 'admin']);

/**
 * → id=1 の admin ロールが作成されます
 *
 * 【手順2：editor ロールを作成する（発展用）】
 */

// 以下のコードをコピーしてtinkerで実行してください：
Role::create(['name' => 'editor']);

/**
 * → id=2 の editor ロールが作成されます
 *
 * 【手順3：id=1 のユーザーに admin ロールを付与する】
 */

// 以下のコードをコピーしてtinkerで実行してください：
$user = User::find(1);
$user->roles()->attach(1);

/**
 * attach（アタッチ）= 関係を追加する（Step7-03で学んだ）
 *
 * 【手順4：付与されているか確認する】
 */

// 以下のコードをコピーしてtinkerで実行してください：
$user = User::find(1);
$user->roles;

/**
 * → ロールの一覧が表示されます
 *   pivot（ピボット）= 中間テーブル（role_user）の情報
 *
 * 【手順5：hasRole() が正しく動くか確認する】
 */

// 以下のコードをコピーしてtinkerで実行してください：
$user = User::find(1);
$user->hasRole('admin');

/**
 * → true が返ってくれば成功！
 */

// 以下のコードをコピーしてtinkerで実行してください：
$user2 = User::find(2);
$user2->hasRole('admin');

/**
 * → false が返ってくれば成功！
 *
 * 確認できたら終了：exit
 */

/**
 * ✅ 【2-9. ExpensePolicy を修正する】
 *
 * update() と delete() に「admin なら無条件で許可」を追加します。
 */

// public function update(User $user, Expense $expense): bool
// {
//     if ($user->hasRole('admin')) {
//         return true;
//     }
//     return $user->id === $expense->user_id;
// }
//
// public function delete(User $user, Expense $expense): bool
// {
//     if ($user->hasRole('admin')) {
//         return true;
//     }
//     return $user->id === $expense->user_id;
// }

/**
 * ✅ 【2-10. ExpenseController の index() を修正する】
 *
 * 【学習中の気づき：タイポに注意】
 *
 * 実装中に以下のタイポが発生しました：
 *   誤：Expenses::with('tag')   ← モデル名が複数形、リレーション名が単数形
 *   正：Expense::with('tags')   ← モデル名は単数形、リレーション名はメソッド名と一致
 *
 * 確認ポイント：
 * - Laravelのモデル名は常に単数形（Expense, User, Role, Tag）
 * - with() の引数はモデルで定義したメソッド名と一致させる（tags() → 'tags'）
 */

// public function index()
// {
//     $user = auth()->user();
//
//     if ($user->hasRole('admin')) {
//         $expenses = Expense::with('tags')->latest()->get();
//     } else {
//         $expenses = Expense::with('tags')
//             ->where('user_id', $user->id)
//             ->latest()
//             ->get();
//     }
//
//     return view('expenses.index', compact('expenses'));
// }

/**
 * ✅ 【2-11. ブラウザで動作確認する】
 *
 * 確認1：test@example.com（admin）でログイン
 *   → 全員分の支出が表示されれば成功
 *
 * 確認2：kosai@mail.com（一般）でログイン
 *   → 自分の支出だけ表示されれば成功
 *
 * 確認3：id=2 でログインしたまま他人の編集ページに直接アクセス
 *   localhost:8080/expenses/2/edit
 *   → 403「この行為は許可されていません。」が表示されれば成功
 *
 * 【動作確認の結果（2026/04/22）】
 *   確認1：admin → 全員分の3件が表示 ✅
 *   確認2：一般ユーザー → 自分の1件だけ表示 ✅
 *   確認3：403エラーが表示 ✅
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📖 【第3部：まとめ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 */

/**
 * ✅ 【3-1. Gate・Policy・ロールの使い分け整理】
 *
 * ┌─────────────────────────────────────────────┐
 * │  使う場面            → 使うもの             │
 * ├─────────────────────────────────────────────┤
 * │ 単純な yes/no の判断  → Gate（ゲート）       │
 * │ 特定モデルへの権限    → Policy（ポリシー）   │
 * │ ユーザーへの役職付与  → ロール（Role）       │
 * └─────────────────────────────────────────────┘
 *
 * 【今日の実装での連携】
 *   User.hasRole('admin')     ← ロールで「admin かどうか」を確認
 *       ↓
 *   ExpensePolicy.update()    ← ロールの結果を使って判断
 *   ExpenseController.index() ← ロールの結果に応じてデータを変える
 */

/**
 * ✅ 【3-2. 用語集】
 *
 * role（ロール）
 *   └─ 役割・役職のこと。例：admin・editor・viewer
 *
 * roles テーブル
 *   └─ 役職情報を保存するテーブル。カラム：id, name
 *
 * role_user テーブル（中間テーブル）
 *   └─ 誰がどの役職を持つかを記録する
 *
 * belongsToMany（ビロングズトゥメニー）
 *   └─ 多対多リレーションを定義するメソッド
 *
 * hasRole()（ハズロール）
 *   └─ 指定した役職を持っているか確認する。true / false を返す
 *
 * contains()（コンテインズ）
 *   └─ 含まれているか確認するコレクションのメソッド。hasRole() 内で使用
 *
 * fillable（フィラブル）
 *   └─ 一括登録（create()）を許可するカラム名のリスト
 *
 * constrained()（コンストレインド）
 *   └─ 参照するテーブルに存在するIDだけ許可する制約
 *
 * cascade（カスケード）
 *   └─ 親が削除されたら子も自動削除される連鎖
 *
 * unique()（ユニーク）
 *   └─ 重複を禁止する
 *
 * pivot（ピボット）
 *   └─ tinker で多対多のデータを確認したとき表示される中間テーブルの情報
 *
 * RBAC（アールバック）
 *   └─ Role-Based Access Control（ロールベースのアクセス制御）の略
 *   └─ 今日実装したような「役職で権限を管理する」設計の総称
 */

/**
 * ✅ 【3-3. Q&A】
 *
 * Q1. なぜ中間テーブルの名前は role_user なのですか？
 *
 * A1. Laravel の命名規則に従っています。
 *     2つのモデル名を「単数形・アルファベット順」でアンダースコアでつなぎます。
 *     Role と User → r < u なので role_user
 *     belongsToMany() に中間テーブル名を書かなくても自動で判断してくれます。
 *
 * Q2. attach() と sync() はどう違いますか？
 *
 * A2. attach = 「追加する」→ 既存の関係はそのままで新しい関係を追加
 *     sync  = 「上書きする」→ 指定したものだけに置き換える（他は削除）
 *     ロールの「追加」なら attach()、「置き換え」なら sync()
 *
 * Q3. is_admin カラムはこれからも使いますか？
 *
 * A3. 今日は is_admin の代わりに hasRole('admin') を使いましたが、
 *     is_admin カラムは削除していません。
 *     今回の目的は「ロールで権限を判断する方法を理解する」ことなので、
 *     完全置き換えはしていません。
 *
 * Q4. roles テーブルのデータはシーダーで作れますか？
 *
 * A4. はい。今日は tinker で手動作成しましたが、
 *     実際のアプリでは Seeder を使うことが多いです。
 *     Seeder については Day10 で学びます。
 *
 * Q5. 今日の内容は実際のLaravelアプリで使われますか？
 *
 * A5. はい。今日実装した仕組みを RBAC（アールバック）と呼びます。
 *     より複雑な権限管理が必要な場合は Laravel-permission などの
 *     外部パッケージを使うこともありますが、
 *     今日はその土台となる考え方を自力で実装しました。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【学習中の気づきまとめ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【気づき1：tinker でのカスタムモデルの名前空間エラー】
 *
 *   発生：Error  Class "Role" not found.
 *   原因：tinker 内でカスタムモデルを使うときは use 文か完全修飾名が必要
 *   解決：use App\Models\Role; を先に宣言する
 *
 *   Step7-04 でも Class "Tag" not found. が発生していた。同じ原因・同じ解決方法。
 *   tinker でカスタムモデルを使う時は use 文を先に宣言する習慣をつける。
 *
 * 【気づき2：タイポの発生パターン】
 *
 *   発生：Expenses::with('tag')
 *   正解：Expense::with('tags')
 *
 *   ① モデル名は常に単数形（Expense, User, Role, Tag）
 *   ② with() の引数はモデルで定義したメソッド名と一致させる（tags() → 'tags'）
 *
 * 【気づき3：コピペ作業への反省】
 *
 *   Day7全体を通じて、実装がコピペ中心になってしまった。
 *   「なぜそう書くか」を理解せずに手を動かす状態になっていた。
 *
 *   今後の学習方針：
 *   - Laravelコーヒーや練習問題で「なぜそう書くか」を言語化する練習をする
 *   - コードを書く前に処理の流れを日本語で説明できるか確認する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🎯 【Day7 全体のまとめ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step7-01：Gate → yes/no のシンプルな認可の仕組み
 * Step7-02：Policy → 特定モデルに対する権限のルールブック
 * Step7-03：多対多（たたいた）→ 両方向で複数持てる関係・中間テーブルの作り方
 * Step7-04：タグ機能の実装 → 多対多を使った実際の機能開発
 * Step7-05：認可と多対多の組み合わせ → ロールシステムで柔軟な権限制御を実現
 *
 * 【重要な気づき】
 *
 * Gate・Policy・ロールは「それぞれ別々の機能」ではなく、
 * 連携して動くことで強力な権限制御ができます。
 *
 * 「ロールで役職を管理」→「Gate・Policyがロールを使って判断」
 *
 * Laravelコーヒーと練習問題で「なぜそう書くか」を定着させましょう。
 */
