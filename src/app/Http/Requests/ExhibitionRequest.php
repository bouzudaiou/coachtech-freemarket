<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required|string|max:255',
            'image_path' => 'required|image|mimes:jpeg,png',
            'catetory_id' => 'required|exists:categories,id',
            'condition' => 'required|in:良好,目立った傷や汚れなし,やや傷や汚れあり,状態が悪い',
            'price' => 'required|numeric|min:0',
        ];

    }

    public function messages(): array
    {
        return [
            'name' => '選択必須',
            'description.required' => '入力必須',
            'description.max255' => '最大文字数255',
            'image_path.required' => 'アップロード必須',
            'image_path.mimes' => '拡張子が.jpegもしくは.png',
            'catetory_id.required' => '選択必須',
            'condition.required' => '選択必須',
            'price.required' => '入力必須',
            'price.numeric' => '数値型',
            'price.min' => '0円以上'
        ];
    }
}
