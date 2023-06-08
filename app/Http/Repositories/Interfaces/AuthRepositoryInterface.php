<?php

namespace App\Http\Repositories\Interfaces;

use Illuminate\Http\JsonResponse;

interface AuthRepositoryInterface
{
    public function register(array $request): object;
}
