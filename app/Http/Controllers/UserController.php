<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponses;

    /**
     * Store a newly created resource in storage.
     */
    public function register(RegisterUserRequest $request)
    {
        $create = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->response('Usuário registrado com sucesso', 201, new UserResource($create));
    }
}
