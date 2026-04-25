<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category'    => 'required|in:食費,交通費,娯楽費,その他',
            'amount'      => 'required|numeric|min:1|max:9999999',
            'description' => 'nullable|string|max:200',
            'spent_at'    => 'required|date|before_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'カテゴリは必ず選択してください',
            'category.in'       => 'カテゴリは食費・交通費・娯楽費・その他から選んでください',
            'amount.required'   => '金額は必ず入力してください',
            'amount.integer'    => '金額は数字で入力してください',
            'amount.min'        => '金額は1円以上で入力してください',
            'amount.max'        => '金額は9,999,999円以下で入力してください',
            'description.max'   => '説明は200文字以内で入力してください',
            'spent_at.required' => '日付は必ず入力してください',
            'spent_at.date'     => '日付の形式が正しくありません',
            'spent_at.before_or_equal' => '日付は今日以前の日付を入力してください',
        ];
    }
}
