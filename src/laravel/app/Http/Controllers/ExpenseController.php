<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Http\Requests\StoreUserRequest;

class ExpenseController extends Controller
{
    /**
     * 一覧表示
     * 全支出データを取得してビューに渡す
     */
    public function index()
    {
        //
    }

    /**
     * 作成フォーム表示
     * 支出登録フォームのページを表示する
     */
    public function create()
    {
        return view('expenses.create');
        // view('expenses.create') = resources/views/expenses/create.blade.php を表示する
    }

    /**
     * 保存処理
     * フォームの入力データをバリデーション済みで受け取り、DBに保存する
     */
    public function store(StoreUserRequest $request)
    {
        Expense::create(array_merge($request->validated(), ['user_id' => auth()->id()]));
        // Expense::create()          = expensesテーブルに新しいレコードを追加する
        // $request->validated()      = バリデーション済みの入力データ（category・amount・dateなど）
        // array_merge()              = 2つの配列を1つにまとめる
        // ['user_id' => auth()->id()] = ログイン中のユーザーIDをuser_idとして追加する
        return redirect('/expenses');
        // 保存後、支出一覧ページにリダイレクト（移動）する
    }

    /**
     * 詳細表示
     * 指定したIDの支出データを1件表示する
     */
    public function show(string $id)
    {
        //
    }

    /**
     * 編集フォーム表示
     * 指定した支出の編集フォームを表示する
     */
    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        // $this->authorize()   = 権限を確認する（警備員に「入っていいか？」を聞く）
        // 'update'             = ExpensePolicyのupdate()メソッドで判断する
        // $expense             = 判断対象のデータ（どの支出か）
        // → ExpensePolicy::update()がtrueなら次へ進む、falseなら403エラー
        return view('expenses.edit', compact('expense'));
        // expenses.edit        = resources/views/expenses/edit.blade.php を表示する
        // compact('expense')   = $expense変数をビューに渡す
    }

    /**
     * 更新処理
     * フォームの入力データをバリデーション済みで受け取り、DBを更新する
     */
    public function update(StoreUserRequest $request, Expense $expense)
    {
        $this->authorize('update', $expense);
        // edit()と同じ理由でauthorizeを確認する
        // URLを直接入力された場合もここで防ぐ（editのauthorizeだけでは不十分）
        $expense->update($request->validated());
        // $expense->update() = 対象の支出レコードをバリデーション済みデータで上書きする
        return redirect()->route('expenses.index');
        // 更新後、支出一覧ページにリダイレクトする
    }

    /**
     * 削除処理
     * 指定した支出をDBから削除する
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        // 'delete' = ExpensePolicyのdelete()メソッドで判断する
        // → 自分の支出でなければ403エラー
        $expense->delete();
        // 対象の支出レコードをDBから削除する
        return redirect()->route('expenses.index');
        // 削除後、支出一覧ページにリダイレクトする
    }
}
