<?php

namespace App\Http\Requests\DespesaRequests;

use App\Traits\HttpResponses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreDespesaRequest extends FormRequest
{
    use HttpResponses;
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
            'user_id' => 'required|exists:users,id',
            'descricao' => 'required|string|max:191',
            'valor' => 'required|numeric|min:0',
            'data' => 'required|date|after:today',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'O ID do usuário é obrigatório.',
            'user_id.exists' => 'O ID do usuário deve existir na tabela de usuários.',
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.string' => 'A descrição deve ser uma string.',
            'descricao.max' => 'A descrição não pode ter mais de 191 caracteres.',
            'valor.required' => 'O valor é obrigatório.',
            'valor.numeric' => 'O valor deve ser numérico.',
            'valor.min' => 'O valor não pode ser negativo.',
            'data.required' => 'A data é obrigatória.',
            'data.date' => 'A data deve ser válida.',
            'data.after' => 'A data deve ser posterior à data atual.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error('Invalid data', 422, $validator->errors()));
    }
}
