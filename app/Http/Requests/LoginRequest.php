<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'mobile'             => 'required|regex:/^1[0-9]{10}$/|exists:users',
            'password'           => 'required|between:6,16',
        ];
    }
    public function messages(){
        return [
            'mobile.required'           => '请填写手机号',
            'mobile.regex'              => '手机号格式不正确',
            'mobile.exists'             => '手机号不存在',
            'password.required'         => '请填写密码',
            'password.between'          => '请注意密码格式',
        ];
    }
}
