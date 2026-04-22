<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    /**
     * viewAny（ビューエニー）
     * 「支出の一覧を見る」権限
     * ログインしているユーザーなら誰でも見られる
     */
    public function viewAny(User $user): bool
    {
        return true; // ログインしていれば全員OK
    }

    /**
     * view（ビュー）
     * 「1件の支出の詳細を見る」権限
     * 自分の支出だけ見られる
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->id === $expense->user_id;
        // ログイン中のユーザーIDと、支出の所有者のIDが一致するか？
    }

    /**
     * create（クリエイト）
     * 「支出を新規作成する」権限
     * ログインしているユーザーなら誰でも作れる
     */
    public function create(User $user): bool
    {
        return true; // ログインしていれば全員OK
    }

    /**
     * update（アップデート）
     * 「支出を編集する」権限
     * 自分の支出だけ編集できる
     */
    public function update(User $user, Expense $expense): bool
    {
        // admin ロールを持つユーザーは誰の Expense でも編集できる
        if ($user->hasRole('admin')) {
            return true;
        }

        // それ以外は自分の Expense だけ編集できる
        return $user->id === $expense->user_id;
        // ログイン中のユーザーIDと、支出の所有者のIDが一致するか？
    }

    /**
     * delete（デリート）
     * 「支出を削除する」権限
     * 自分の支出だけ削除できる
     */
    public function delete(User $user, Expense $expense): bool
    {

        // admin ロールを持つユーザーは誰の Expense でも削除できる
        if ($user->hasRole('admin')) {
            return true;
        }
        // それ以外は自分の Expense だけ削除できる
        return $user->id === $expense->user_id;
        // ログイン中のユーザーIDと、支出の所有者のIDが一致するか？
    }
}
