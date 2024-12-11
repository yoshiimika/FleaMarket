<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'avatar.image' => '画像ファイルを選択してください',
            'avatar.mimes' => '画像はjpeg、png形式でアップロードしてください',
            'avatar.max' => '画像サイズは2MB以内でアップロードしてください',
            'name.required' => 'ユーザー名を入力してください',
            'zip.required' => '郵便番号を入力してください',
            'zip.regex' => '郵便番号はハイフンを含む形式で入力してください（例: 123-4567）',
            'address.required' => '住所を入力してください',
        ];
    }
}
