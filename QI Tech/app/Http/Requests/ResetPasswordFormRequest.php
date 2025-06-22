<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ResetPasswordFormRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'token'=>'required',
            'type'=>'required',
            'password' => 'string|min:8|max:80|nullable',
            'c_password' => 'same:password',
        ];
    }

    public function getData()
    {
        $data = $this->only(['token','type','password']);
        $data['password']=Hash::make($data['password']);
        return $data;
    }
}
