<?php

/**
 * 📘 Day10 教材（Step10-02：実際のテストを書く）
 *
 * この教材では「実際にテストコードを書いて動かす」方法を学びます。
 * Step10-01で学んだ知識を使って、expensesとtasksの機能をテストします。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * 🧠 【今日学ぶこと】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Step10-01では「テストとは何か」「どうやって動かすか」を学びました。
 * Step10-02では「実際に書く」がテーマです。
 *
 * 【今日やること】
 * 1. Factoryファイル（テスト用のダミーデータ作成ツール）を作る
 * 2. RefreshDatabaseトレイトを使って毎回クリーンな状態でテストする
 * 3. expenses（支出）の基本操作をテストするコードを書く
 *
 * 【たとえ話：テスト工場】
 *
 * テストを書くには「テスト用のダミーデータ」が必要です。
 * 毎回手動でデータを作るのは大変なので、
 * 「自動でダミーデータを量産してくれる工場」を用意します。
 * それが Factory（ファクトリー）です。
 *
 * - Factory（ファクトリー）= 工場。テスト用のダミーデータを自動で作る仕組み
 * - RefreshDatabase（リフレッシュデータベース）= 各テスト前にDBを綺麗にリセットする機能
 *
 * 【今日学ぶ主な内容】
 * ① Factoryとは何か（なぜ必要か）
 * ② ExpenseFactoryを作る
 * ③ RefreshDatabaseトレイトを有効にする
 * ④ テストコードの書き方（4ステップ：準備→操作→確認→後片付け）
 * ⑤ 実際にテストを実行する
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【1. Factoryとは？なぜ必要？】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Factory（ファクトリー） = 「テスト用ダミーデータの工場」
 *
 * 【問題：テストにはデータが必要】
 *
 * テストで「ユーザーが支出を登録できるか確認したい」場合、
 * まずユーザーデータが必要です。
 *
 * 手動で作ると：
 *   User::create(['name' => '山田太郎', 'email' => '...', 'password' => '...']);
 * → テストのたびに毎回書かないといけない。大変！
 *
 * Factoryを使うと：
 *   User::factory()->create();
 * → 1行でダミーユーザーを自動生成！
 *
 * 【Factoryが自動生成する内容の例】
 *
 * UserFactoryは最初からLaravelに入っています：
 *   name  → 'Test User'（固定）
 *   email → 'test@example.com'（固定）または fake()->unique()->safeEmail（ランダム）
 *
 * ExpenseFactoryは今回私たちが作ります。
 *
 * 【保存場所】
 * database/factories/UserFactory.php   → 最初から存在
 * database/factories/ExpenseFactory.php → 今回作成
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【2. ExpenseFactoryを作る】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【手順2-1：コマンドでFactoryファイルを生成する】
 *
 * 以下のコマンドでExpenseFactoryのファイルを自動生成します。
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan make:factory ExpenseFactory --model=Expense

/**
 * 実行すると以下のファイルが生成されます：
 * database/factories/ExpenseFactory.php
 *
 * 【手順2-2：ExpenseFactory.phpを編集する】
 *
 * 生成されたファイルを開いて、以下の内容に書き換えてください。
 */

// 以下がExpenseFactory.phpの完成形です。VSCodeで開いて内容を置き換えてください：

/*
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'category'    => $this->faker->randomElement(['食費', '交通費', '日用品', '趣味', '外食']),
            'amount'      => $this->faker->numberBetween(100, 50000),
            'description' => $this->faker->optional()->sentence(),
            'spent_at'    => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'image_path'  => null,
        ];
    }
}
*/

/**
 * 【コードの意味を解説】
 *
 * User::factory()
 * → 「ユーザーも自動で作って関連づける」という意味
 * → Expense（支出）は必ず誰かのものなので、ユーザーも一緒に作る
 *
 * $this->faker->randomElement([...])
 * → faker（フェイカー）= 「偽物（フェイク）データを作ってくれる道具」
 * → randomElement = 配列の中からランダムに1つ選ぶ
 *
 * $this->faker->numberBetween(100, 50000)
 * → 100から50000の間でランダムな数字を生成
 *
 * $this->faker->optional()->sentence()
 * → optional() = 「なくてもいい（null になる場合もある）」という意味
 * → sentence() = ランダムな文章を生成
 *
 * $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d')
 * → 「1年前から今日まで」の間でランダムな日付を生成
 *
 * 'image_path' => null
 * → テスト時は画像なしでOK
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【3. テストファイルを作る】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【手順3-1：テストファイルを生成する】
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan make:test ExpenseTest

/**
 * 実行すると以下のファイルが生成されます：
 * tests/Feature/ExpenseTest.php
 *
 * 【手順3-2：ExpenseTest.phpを編集する】
 *
 * 生成されたファイルを開いて、以下の内容に書き換えてください。
 */

// 以下がExpenseTest.phpの完成形です。VSCodeで開いて内容を置き換えてください：

/*
<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // テスト1：支出一覧ページが表示できるか
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    public function test_expenses_index_is_accessible_when_logged_in(): void
    {
        // 準備：テスト用ユーザーを作成
        $user = User::factory()->create();

        // 操作：ログイン状態で /expenses にアクセス
        $response = $this->actingAs($user)->get('/expenses');

        // 確認：200（正常表示）が返ってくるか
        $response->assertStatus(200);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // テスト2：ログインしていない場合はリダイレクトされるか
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    public function test_expenses_index_redirects_when_not_logged_in(): void
    {
        // 操作：ログインせずに /expenses にアクセス
        $response = $this->get('/expenses');

        // 確認：302（リダイレクト）が返ってくるか
        $response->assertStatus(302);
        // 確認：ログインページにリダイレクトされるか
        $response->assertRedirect('/login');
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // テスト3：支出を新規登録できるか
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    public function test_user_can_create_expense(): void
    {
        // 準備：テスト用ユーザーを作成
        $user = User::factory()->create();

        // 操作：ログイン状態で支出を登録
        $response = $this->actingAs($user)->post('/expenses', [
            'category'    => '食費',
            'amount'      => 1500,
            'description' => 'テスト用の支出',
            'spent_at'    => '2026-01-01',
        ]);

        // 確認：支出一覧ページにリダイレクトされるか
        $response->assertRedirect('/expenses');

        // 確認：DBにデータが保存されているか
        $this->assertDatabaseHas('expenses', [
            'user_id'  => $user->id,
            'category' => '食費',
            'amount'   => 1500,
        ]);
    }

    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    // テスト4：他人の支出は編集できないか（認可のテスト）
    // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    public function test_user_cannot_edit_other_users_expense(): void
    {
        // 準備：ユーザーAとユーザーBを作成
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // 準備：ユーザーAの支出を作成
        $expense = Expense::factory()->create(['user_id' => $userA->id]);

        // 操作：ユーザーBとしてユーザーAの支出編集ページにアクセス
        $response = $this->actingAs($userB)->get("/expenses/{$expense->id}/edit");

        // 確認：403（アクセス禁止）が返ってくるか
        $response->assertStatus(403);
    }
}
*/

/**
 * 【コードの意味を解説】
 *
 * use RefreshDatabase;
 * → Step10-01で学んだ「毎回DBをリセットする」機能を有効にする
 * → テストクラスの中、最初のpublic functionの前に1行書くだけ
 *
 * $this->actingAs($user)
 * → actingAs（アクティングアズ）= 「〜として行動する」という意味
 * → 「$userとしてログインした状態でアクセスする」という意味
 *
 * $response->assertStatus(200)
 * → assertStatus（アサートステータス）= 「ステータスコードが〇〇であることを確認」
 * → 200 = 「正常に表示できた」
 * → 302 = 「リダイレクト（別のページに転送）」
 * → 403 = 「アクセス禁止」
 *
 * $this->assertDatabaseHas('expenses', [...])
 * → assertDatabaseHas（アサートデータベースハズ）= 「DBにこのデータがあることを確認」
 * → 'expenses' = テーブル名
 * → [...] = 探すデータの条件
 *
 * User::factory()->create()
 * → 手順2で作ったFactoryを使って、テスト用のユーザーを実際にDBに保存する
 *
 * Expense::factory()->create(['user_id' => $userA->id])
 * → ExpenseFactoryを使って、ユーザーAの支出を作る
 * → ['user_id' => $userA->id] = 「user_idだけFactoryの自動値を上書きする」という意味
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【4. テストを実行する】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * 【全テスト実行】
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan test

/**
 * 【ExpenseTestのみ実行したい場合】
 */

// 以下のコマンドをターミナルで実行してください：
// docker compose exec php php artisan test --filter=ExpenseTest

/**
 * 【成功時の表示例】
 *
 *    PASS  Tests\Feature\ExpenseTest
 *   ✓ expenses index is accessible when logged in          0.20s
 *   ✓ expenses index redirects when not logged in          0.05s
 *   ✓ user can create expense                              0.10s
 *   ✓ user cannot edit other users expense                 0.08s
 *
 *   Tests:    4 passed (4 assertions)
 *   Duration: 0.53s
 *
 * 【失敗時の表示例】
 *
 *    FAIL  Tests\Feature\ExpenseTest
 *   ✕ user can create expense
 *
 *   Expected status code 302 but received 422.
 *   → 302（リダイレクト）を期待していたが、422（バリデーションエラー）が返ってきた
 *
 *   テストが失敗したら「何が期待値と違ったか」が表示されるので、
 *   コードを修正して再度実行します。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【5. Q&A】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Q1. RefreshDatabaseを使うと本番のDBのデータは消えますか？
 *
 * A1. 消えません。
 *     phpunit.xmlに書いた設定（SQLiteのメモリDB）のみに対して動作します。
 *     本番DBや開発DBには一切影響しません。
 *     テスト専用の「一時的なDB」を使い捨てにする仕組みです。
 *
 * Q2. factory()->create() と factory()->make() の違いは何ですか？
 *
 * A2. create() はDBに保存します。make() はDBに保存しません。
 *
 *     User::factory()->create()  → DBに保存される（IDが割り当てられる）
 *     User::factory()->make()    → メモリ上だけ（DBには保存されない）
 *
 *     テストで「DBにデータがあるか確認したい」場合は create() を使います。
 *     DBに保存する前の状態を使いたい場合は make() を使います。
 *
 * Q3. テスト4でなぜ403が返ってくるのですか？
 *
 * A3. Day7で作ったExpensePolicyが動いているからです。
 *
 *     ExpensePolicy には「自分の支出しか編集できない」ルールが書いてあります。
 *     ユーザーBがユーザーAの支出にアクセスしようとすると、
 *     Policyが「ダメ」と判断して403（アクセス禁止）を返します。
 *
 *     テストはこの「認可の仕組みが正しく動いているか」を確認しています。
 *
 * Q4. メソッド名（test_user_can_create_expense）の書き方にルールがありますか？
 *
 * A4. はい。2つのルールがあります。
 *
 *     ルール1：メソッド名の先頭に「test_」をつける
 *     → これがないとPHPUnitがテストと認識しません
 *
 *     ルール2：アンダースコアで区切った英語を使う（スネークケース）
 *     → test_user_can_create_expense（読めば意味がわかる名前にする）
 *
 *     良い例：test_user_cannot_edit_other_users_expense
 *     悪い例：test1（何をテストしているかわからない）
 *
 * Q5. テストファイルはどこに保存すればよいですか？
 *
 * A5. 機能テスト（ブラウザ操作のような動作確認）は tests/Feature/ に保存します。
 *     単体テスト（1つのクラスや関数だけのテスト）は tests/Unit/ に保存します。
 *
 *     今回作ったExpenseTest（ページ表示・登録・認可の確認）は
 *     HTTPリクエストを使う「機能テスト」なので tests/Feature/ に入れています。
 */

/**
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 * ✅ 【6. 用語集】
 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 *
 * Factory（ファクトリー）
 * └─ テスト用ダミーデータを自動生成する「工場」
 *
 * fake()・$this->faker
 * └─ ランダムなダミーデータを作ってくれる道具
 *    randomElement（ランダムエレメント）= 配列からランダムに選ぶ
 *    numberBetween（ナンバービトウィーン）= 指定した範囲の数字を生成
 *    optional（オプショナル）= null になる場合もある（任意項目）
 *
 * RefreshDatabase（リフレッシュデータベース）
 * └─ 各テスト前にDBをリセットする機能。use RefreshDatabase; で有効化
 *
 * actingAs（アクティングアズ）
 * └─ 「〜としてログインした状態」でテストする
 *
 * assertStatus（アサートステータス）
 * └─ HTTPステータスコードを確認する
 *    200 = 正常表示 / 302 = リダイレクト / 403 = アクセス禁止 / 404 = 見つからない
 *
 * assertDatabaseHas（アサートデータベースハズ）
 * └─ 「このデータがDBに存在するか」を確認する
 *
 * assertRedirect（アサートリダイレクト）
 * └─ 「このURLにリダイレクトされるか」を確認する
 */
