<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Helpers\ResponseHelper;
use App\Http\Services\AuthService;
use Carbon\Factory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    private $responseHelper;
    private $authService;

    public function __construct(ResponseHelper $responseHelper, AuthService $authService)
    {
        $this->responseHelper = $responseHelper;
        $this->authService = $authService;
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|max:8|confirmed',
        ]);

        if ($validator->fails()) {
            Log::info($validator->errors()->first());
            return $this->responseHelper->validataionFail($validator->errors()->first());
        }

        $this->authService->register($request->all());

        return  $this->responseHelper->response(true, Response::HTTP_CREATED, trans('messages.sucess.USER_REGISTERED_SUCCESSFULLY'));;
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6|max:8',
        ]);

        if ($validator->fails()) {
            // Log::info($validator->errors()->first());
            return $this->responseHelper->validataionFail($validator->errors()->first());
        }

        $token = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        if (!$token) {
            return $this->responseHelper->response(true, Response::HTTP_UNAUTHORIZED, trans('messages.error.UNAUTHORIZED_ACCESS_ERROR'));
        }
        return $this->responseHelper->response(true, Response::HTTP_OK, trans('messages.sucess.USER_LOGIN_SUCCESSFULLY'),$this->respondWithToken($token));
    }

    public function forgotPassword(Request $request):JsonResponse{
       $validator = Validator::make($request->all(),[
        'email' => 'required|string|email'
       ]);

       if($validator->fails()){
            return $this->responseHelper->validataionFail(($validator->errors()->first()));
       }

    $this->authService->forgotPassword($request->input('email'));

    }

    public function respondWithToken($token){
        $role = auth()->user()->getRoleNames();
        return [
            'access_token'=>$token,
            'token_type'=>'bearer',
            'role'=>$role,
            'user'=>auth()->user(),
            'expires_in'=> auth()->factory()->getTTL() * 60 
        ];
    }

}
