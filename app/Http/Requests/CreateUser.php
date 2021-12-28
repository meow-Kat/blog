<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// class CreateUser extends FormRequest 改成下面
class CreateUser extends APIRequest // 拿之前做的
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',    // ↓ 唯一值，針對 users
            'email' => 'required|string|email|unique:users',
                                        // ↓ 兩個一致才會通過 ( password 和 password_confirmaiton )
            'password' => 'required|string|confirmed'
        ];
    }
}
