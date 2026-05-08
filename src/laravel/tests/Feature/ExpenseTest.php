<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
