<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{

    public function register(array $request): object
    {
        return User::create([
            'name' => $request['firstname'] . ' ' . $request['lastname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
    }
}
