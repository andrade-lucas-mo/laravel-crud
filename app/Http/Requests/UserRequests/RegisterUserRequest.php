<?php

namespace App\Http\Requests\UserRequests;

use App\Traits\HttpResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    use HttpResponses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.string' => 'O email deve ser uma string.',
            'email.email' => 'O email deve ser um endereço de email válido.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            'email.unique' => 'O email já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha deve ser uma string.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error('Invalid data', 422, $validator->errors()));
    }
}
