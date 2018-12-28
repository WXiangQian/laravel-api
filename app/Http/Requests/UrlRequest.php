<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrlRequest extends FormRequest
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
            'url_long' => 'required|url',
        ];
    }

    public function messages()
    {
        return [
            'url_long.required' => '请填写长链接',
            'url_long.url' => 'url格式不正确',
        ];
    }
}
