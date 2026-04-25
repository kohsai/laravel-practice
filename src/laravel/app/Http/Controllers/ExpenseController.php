<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Expense;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    // 一覧表示
    public function index()
    {
        $user = auth()->user();
        // admin ロールを持つユーザーは全員分の支出を表示
        if ($user->hasRole('admin')) {
            $expenses = Expense::with('tags')->latest()->get();
        } else {
            // 一般ユーザーは自分の支出だけ表示
            $expenses = Expense::with('tags')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }
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

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('expenses', 'public');
        }

        $expense = Expense::create(
            array_merge($request->validated(), [
                'user_id' => auth()->id(),
                'image_path' => $imagePath,
            ])
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

        // 【パターン①：「画像を削除するチェック」が入っている場合】
        if ($request->input('delete_image') === '1') {

            // 古い画像ファイルをストレージから削除する
            if ($expense->image_path) {
                Storage::disk('public')->delete($expense->image_path);
            }

            // image_path を null（空）にしてデータを更新する
            $expense->update(array_merge($request->validated(), ['image_path' => null]));

            // 【パターン②：新しい画像ファイルが送られてきた場合】
        } elseif ($request->hasFile('image')) {

            // 古い画像ファイルがあれば削除する
            if ($expense->image_path) {
                Storage::disk('public')->delete($expense->image_path);
            }

            // 新しい画像を保存して、そのパスを取得する
            $imagePath = $request->file('image')->store('expenses', 'public');

            // image_path を新しいパスで更新する
            $expense->update(array_merge($request->validated(), ['image_path' => $imagePath]));

            // 【パターン③：画像に変更なし】
        } else {
            // image_path はそのまま（validated() に含まれないので変わらない）
            $expense->update($request->validated());
        }

        // タグを同期する（タグの処理はパターンに関係なく同じ）
        $expense->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('expenses.index')->with('success', '支出を更新しました');
    }
}
