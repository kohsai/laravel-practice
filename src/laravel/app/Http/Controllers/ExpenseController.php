<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Expense;
use App\Http\Requests\StoreUserRequest;

class ExpenseController extends Controller
{
    // 一覧表示
    public function index()
    {
        $expenses = Expense::with('tags')
            ->where('user_id', auth()->id())
            ->latest('spent_at')
            ->get();

        return view('expenses.index', compact('expenses'));
    }

    // 作成フォーム表示
    public function create()
    {
        $tags = Tag::all();

        return view('expenses.create', compact('tags'));
    }

    // 保存処理
    public function store(StoreUserRequest $request)
    {
        $expense = Expense::create(
            array_merge($request->validated(), ['user_id' => auth()->id()])
        );

        $expense->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('expenses.index')->with('success', '支出を登録しました');
    }

    // 詳細表示
    public function show(string $id)
    {
        //
    }

    // 編集フォーム表示
    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        $tags = Tag::all();
        $selectedTagIds = $expense->tags()->pluck('tags.id')->toArray();

        return view('expenses.edit', compact('expense', 'tags', 'selectedTagIds'));
    }

    // 更新処理
    public function update(StoreUserRequest $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $expense->update($request->validated());
        $expense->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('expenses.index')->with('success', '支出を更新しました');
    }

    // 削除処理
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', '支出を削除しました');
    }
}
