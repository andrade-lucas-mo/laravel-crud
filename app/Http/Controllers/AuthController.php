<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))){
            $token = $request->user()->createToken(
                'user',
                ['despesas-manager']
            );
            return $this->response('Autorizado', 200, ['token' => $token->plainTextToken]);
        }
        return $this->response('Não Autorizado', 403, ['autorizacao' => 'Usuário ou senha Inválidos']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->response('Token Revogado', 200);
    }
}
