<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Log;

class AuthService
{

    private $authRepository;

    function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $request)
    {
        $role = config('constants.role.USER');  
        $user = $this->authRepository->register($request);
        $user->assignRole($role);
    }
}
