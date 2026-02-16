<?php
/**
 * 📘 Day5 教材（Step5-06：リレーションの実践 - 実際に定義して動作確認）
 * 
 * この教材では「実際にモデルにリレーションを定義して動作確認する」方法を学びます
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * Step5-05で「リレーションの概念」を学びました。
 * Step5-06では「実際にモデルファイルを編集してリレーションを定義し、動作確認する」ことを学びます。
 * 
 * 【やること】
 * 1. Userモデルにexpensesメソッドを追加（hasMany）
 * 2. Expenseモデルにuserメソッドを追加（belongsTo）
 * 3. tinkerで動作確認
 * 4. データが正しく取得できることを確認
 * 
 * 【Step5-05の復習】
 * - hasMany（ハズメニー）= 1人は複数の〇〇を持つ
 * - belongsTo（ビロングストゥー）= 1つの〇〇は1人に属する
 * - 外部キー（user_id）で関連付け
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【作業1: Userモデルの編集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * 【ファイル】
 * src/laravel/app/Models/User.php
 * 
 * 【追加する場所】
 * クラスの中、他のメソッドの後（最後の方でOK）
 * 
 * 【追加するコード】
 * 以下のコードをUserモデルに追加してください。
 * 
 * 注意：このコードは説明用です。実際のコードは後で提示します。
 */

/**
 * // User.phpに追加するコード（説明用）
 * 
 * public function expenses()
 * {
 *     return $this->hasMany(Expense::class);
 * }
 * 
 * 【コードの意味】
 * 
 * public function expenses()
 * ↓
 * 「expensesという名前の公開されたメソッド（命令）を作ります」
 * 
 * return $this->hasMany(Expense::class);
 * ↓
 * 「このユーザー（$this）は、複数のExpense（支出記録）を持っている、という関係を返します」
 * 
 * 【なぜexpensesという名前？】
 * - 1人のユーザーは「複数の」支出記録を持つ
 * - だから複数形（expenses）を使う
 * - 単数形（expense）ではない
 * 
 * 【なぜExpense::classと書く？】
 * - Expenseモデルと関連付けることを明示
 * - ::class = 「Expenseというクラス（設計図）」という意味
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【作業2: Expenseモデルの編集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * 【ファイル】
 * src/laravel/app/Models/Expense.php
 * 
 * 【追加する場所】
 * クラスの中、$fillableの後
 * 
 * 【追加するコード】
 * 以下のコードをExpenseモデルに追加してください。
 * 
 * 注意：このコードは説明用です。実際のコードは後で提示します。
 */

/**
 * // Expense.phpに追加するコード（説明用）
 * 
 * public function user()
 * {
 *     return $this->belongsTo(User::class);
 * }
 * 
 * 【コードの意味】
 * 
 * public function user()
 * ↓
 * 「userという名前の公開されたメソッド（命令）を作ります」
 * 
 * return $this->belongsTo(User::class);
 * ↓
 * 「この支出記録（$this）は、1人のUser（ユーザー）に属している、という関係を返します」
 * 
 * 【なぜuserという名前？】
 * - 1つの支出記録は「1人の」ユーザーに属する
 * - だから単数形（user）を使う
 * - 複数形（users）ではない
 * 
 * 【なぜUser::classと書く？】
 * - Userモデルと関連付けることを明示
 * - ::class = 「Userというクラス（設計図）」という意味
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【実際に追加するコード】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * それでは実際にコードを追加します。
 * VSCodeで該当ファイルを開いて、以下の手順で編集してください。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 【手順1: Userモデルの編集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * 1. VSCodeで「src/laravel/app/Models/User.php」を開く
 * 
 * 2. クラスの最後の方（protected $castsの後くらい）に移動
 * 
 * 3. 以下のコードを追加（コピペしてください）：
 */

// User.phpに追加するコード：
// public function expenses()
// {
//     return $this->hasMany(Expense::class);
// }

/**
 * 4. ファイルを保存（Ctrl+S または Cmd+S）
 * 
 * 【追加後のUser.phpのイメージ】
 * 
 * class User extends Authenticatable
 * {
 *     // ... 他のコード ...
 *     
 *     protected $casts = [
 *         'email_verified_at' => 'datetime',
 *         'password' => 'hashed',
 *     ];
 *     
 *     // ← ここに追加
 *     public function expenses()
 *     {
 *         return $this->hasMany(Expense::class);
 *     }
 * }
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 【手順2: Expenseモデルの編集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * 1. VSCodeで「src/laravel/app/Models/Expense.php」を開く
 * 
 * 2. $fillableの後に移動
 * 
 * 3. 以下のコードを追加（コピペしてください）：
 */

// Expense.phpに追加するコード：
// public function user()
// {
//     return $this->belongsTo(User::class);
// }

/**
 * 4. ファイルを保存（Ctrl+S または Cmd+S）
 * 
 * 【追加後のExpense.phpのイメージ】
 * 
 * class Expense extends Model
 * {
 *     use HasFactory;
 *     
 *     protected $fillable = [
 *         'user_id',
 *         'amount',
 *         'description',
 *         'expense_date',
 *     ];
 *     
 *     // ← ここに追加
 *     public function user()
 *     {
 *         return $this->belongsTo(User::class);
 *     }
 * }
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【動作確認1: 親から子を取得（User → Expenses）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * モデルの編集が完了したら、tinkerで動作確認します。
 * 
 * 【tinkerの起動】
 * ターミナルで以下のコマンドを実行：
 */

// ターミナルで実行：
// docker exec -it laravel-practice-php-1 bash
// php artisan tinker

/**
 * 【確認1: ID=1のユーザーの支出記録を全部取得】
 */

// 以下のコードをコピーしてtinkerで実行してください：
User::find(1)->expenses;

/**
 * → 実行結果（例）：
 * 
 * Illuminate\Database\Eloquent\Collection {#4520
 *   all: [
 *     App\Models\Expense {#4521
 *       id: 1,
 *       user_id: 1,
 *       amount: 1500,
 *       description: "ランチ代",
 *       expense_date: "2025-02-15",
 *       created_at: "2025-02-15 10:00:00",
 *       updated_at: "2025-02-15 10:00:00",
 *     },
 *   ],
 * }
 * 
 * 【このコードの意味】
 * 
 * User::find(1)
 * ↓
 * 「ID=1のユーザーを探して」
 * 
 * ->expenses
 * ↓
 * 「そのユーザーの支出記録を全部持ってきて」
 * 
 * つまり「ID=1のユーザーの支出記録を全部見せて」という意味です。
 * 
 * 【たとえ話】
 * - User::find(1) = 「山田太郎さんの名簿カードを出して」
 * - ->expenses = 「山田太郎さんの答案用紙を全部持ってきて」
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【動作確認2: 子から親を取得（Expense → User）】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 */

// 以下のコードをコピーしてtinkerで実行してください：
Expense::find(1)->user;

/**
 * → 実行結果（例）：
 * 
 * App\Models\User {#4522
 *   id: 1,
 *   name: "山田太郎",
 *   email: "yamada@example.com",
 *   email_verified_at: null,
 *   created_at: "2025-02-15 09:00:00",
 *   updated_at: "2025-02-15 09:00:00",
 * }
 * 
 * 【このコードの意味】
 * 
 * Expense::find(1)
 * ↓
 * 「ID=1の支出記録を探して」
 * 
 * ->user
 * ↓
 * 「その支出記録の持ち主（ユーザー）を持ってきて」
 * 
 * つまり「ID=1の支出記録の持ち主は誰？」という意味です。
 * 
 * 【たとえ話】
 * - Expense::find(1) = 「答案用紙No.1を出して」
 * - ->user = 「この答案用紙の持ち主の名簿カードを持ってきて」
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【動作確認3: 名前だけ取得】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * リレーションを使って、特定の項目だけを取得することもできます。
 */

// 以下のコードをコピーしてtinkerで実行してください：
Expense::find(1)->user->name;

/**
 * → 実行結果（例）：
 * 
 * "山田太郎"
 * 
 * 【このコードの意味】
 * 
 * Expense::find(1)->user
 * ↓
 * 「ID=1の支出記録の持ち主を取得」
 * 
 * ->name
 * ↓
 * 「その人の名前だけ取り出す」
 * 
 * 【たとえ話】
 * 「答案用紙No.1の持ち主の名簿カードから、名前だけ読み取る」
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【動作確認4: 件数を取得】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * 「このユーザーは支出記録を何件持っているか？」も簡単に調べられます。
 */

// 以下のコードをコピーしてtinkerで実行してください：
User::find(1)->expenses()->count();

/**
 * → 実行結果（例）：
 * 
 * 1
 * 
 * 【このコードの意味】
 * 
 * User::find(1)->expenses()
 * ↓
 * 「ID=1のユーザーの支出記録を取得する準備」
 * 注意：expenses()の後ろに()が付いています
 * 
 * ->count()
 * ↓
 * 「件数を数える」
 * 
 * 【expenses と expenses() の違い】
 * 
 * User::find(1)->expenses
 * ↓
 * すぐにデータを取得（Collection）
 * 
 * User::find(1)->expenses()
 * ↓
 * クエリビルダを返す（条件を追加できる）
 * 
 * 【たとえ話】
 * - expenses = 「山田太郎さんの答案用紙を全部持ってきた状態」
 * - expenses() = 「山田太郎さんの答案用紙を探す準備ができた状態」
 * 
 * expenses()の方が「count()で数える」「where()で絞り込む」などの
 * 追加の操作ができます。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【動作確認5: 条件をつけて取得】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * 「このユーザーの支出記録の中で、金額が1000円以上のものだけ」
 * のように条件をつけることもできます。
 */

// 以下のコードをコピーしてtinkerで実行してください：
User::find(1)->expenses()->where('amount', '>=', 1000)->get();

/**
 * → 実行結果（例）：
 * 
 * Illuminate\Database\Eloquent\Collection {#4523
 *   all: [
 *     App\Models\Expense {#4524
 *       id: 1,
 *       user_id: 1,
 *       amount: 1500,
 *       description: "ランチ代",
 *       expense_date: "2025-02-15",
 *       created_at: "2025-02-15 10:00:00",
 *       updated_at: "2025-02-15 10:00:00",
 *     },
 *   ],
 * }
 * 
 * 【このコードの意味】
 * 
 * User::find(1)->expenses()
 * ↓
 * 「ID=1のユーザーの支出記録を取得する準備」
 * 
 * ->where('amount', '>=', 1000)
 * ↓
 * 「金額が1000円以上のものだけに絞り込む」
 * 
 * ->get()
 * ↓
 * 「実際にデータを取得」
 * 
 * 【たとえ話】
 * 「山田太郎さんの答案用紙の中で、点数が1000点以上のものだけ持ってきて」
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 💡 【まとめ】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * 【今日学んだこと】
 * 
 * 1. **Userモデルにexpenses()メソッドを追加**
 *    - hasMany(Expense::class)
 *    - 「1人のユーザーは複数の支出記録を持つ」
 * 
 * 2. **Expenseモデルにuser()メソッドを追加**
 *    - belongsTo(User::class)
 *    - 「1つの支出記録は1人のユーザーに属する」
 * 
 * 3. **親から子を取得**
 *    - User::find(1)->expenses
 *    - User::find(1)->expenses()->get()
 * 
 * 4. **子から親を取得**
 *    - Expense::find(1)->user
 *    - Expense::find(1)->user->name
 * 
 * 5. **条件をつけて取得**
 *    - User::find(1)->expenses()->where('amount', '>=', 1000)->get()
 *    - User::find(1)->expenses()->count()
 * 
 * 【実務での使い道】
 * 
 * - ユーザーの支出一覧ページ
 *   → User::find($userId)->expenses
 * 
 * - 支出記録の詳細ページで「誰の支出か」を表示
 *   → Expense::find($expenseId)->user->name
 * 
 * - 月別の支出記録
 *   → User::find($userId)->expenses()->where('expense_date', '>=', '2025-02-01')->get()
 * 
 * - ユーザーの支出記録の合計件数
 *   → User::find($userId)->expenses()->count()
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🔍 【よくある質問】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 */

/**
 * Q1. expenses と expenses() の違いは何ですか？
 * 
 * A1. expensesはすぐにデータを取得、expenses()は条件を追加できます。
 * 
 * User::find(1)->expenses
 * → すぐにデータを全部取得（Collection）
 * 
 * User::find(1)->expenses()
 * → クエリビルダを返す（where()やcount()が使える）
 * 
 * 【使い分け】
 * - 全部取得したい → expenses
 * - 条件をつけたい → expenses()->where()...->get()
 * - 件数を数えたい → expenses()->count()
 */

/**
 * Q2. User::classってなんですか？
 * 
 * A2. 「Userというクラス（設計図）」を指定する書き方です。
 * 
 * hasMany(Expense::class)
 * ↓
 * 「Expenseモデルと関連付ける」という意味
 * 
 * 【なぜ::classを使う？】
 * - 文字列で'App\Models\Expense'と書くより安全
 * - タイプミスがあればエラーで教えてくれる
 * - IDE（VSCode等）が自動補完してくれる
 */

/**
 * Q3. リレーションを定義すると、何が自動的に行われますか？
 * 
 * A3. Laravelが自動的にSQLを組み立ててくれます。
 * 
 * User::find(1)->expenses
 * ↓
 * Laravelが自動的に以下のSQLを実行：
 * SELECT * FROM expenses WHERE user_id = 1
 * 
 * 自分でSQLを書く必要はありません。
 */

/**
 * Q4. 外部キー（user_id）は自動で判断されますか？
 * 
 * A4. はい、Laravelが命名規則から自動判断します。
 * 
 * belongsTo(User::class)
 * ↓
 * Laravelは「user_id」という外部キーを自動的に使います
 * 
 * 【命名規則】
 * - Userモデル → user_id
 * - Postモデル → post_id
 * - Commentモデル → comment_id
 * 
 * もし異なる名前を使いたい場合は明示的に指定できます：
 * belongsTo(User::class, 'owner_id')
 */

/**
 * Q5. リレーションは双方向で定義する必要がありますか？
 * 
 * A5. 必須ではありませんが、両方定義すると便利です。
 * 
 * 【片方だけ定義した場合】
 * - Userにexpenses()だけ定義
 *   → User::find(1)->expensesは使える
 *   → Expense::find(1)->userは使えない
 * 
 * 【両方定義した場合】
 * - Userにexpenses()、Expenseにuser()を定義
 *   → どちらの方向でもデータを取得できる
 *   → 実務では両方定義するのが一般的
 */

/**
 * Q6. リレーションを定義したら、マイグレーションも変更する必要がありますか？
 * 
 * A6. いいえ、マイグレーションは変更不要です。
 * 
 * マイグレーション（テーブル構造）はすでに作成済みで、
 * user_idカラムも存在しています。
 * 
 * リレーションの定義は「モデルに書くだけ」でOKです。
 * データベース自体には何も変更しません。
 */

/**
 * Q7. もし支出記録がない場合、User::find(1)->expensesは何が返りますか？
 * 
 * A7. 空のCollection（からっぽの箱）が返ります。
 * 
 * → 実行結果：
 * Illuminate\Database\Eloquent\Collection {#4525
 *   all: [],
 * }
 * 
 * エラーにはなりません。
 * 
 * 【たとえ話】
 * 「山田太郎さんの答案用紙を持ってきて」
 * → 答案用紙がない場合、「空の箱」が返ってくる
 * → エラーではなく、「0件」という結果
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 📝 【次のStep】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 
 * Step5-06でリレーションの実践を学びました。
 * 
 * 次は「Laravelコーヒー」でDay5全体の復習を行います。
 * 
 * Laravelコーヒーでは、Day1～Day5で学んだことを
 * ごちゃまぜで出題しますので、復習になります。
 * 
 * その後、Day5完了報告を作成し、Day6に進みます。
 */