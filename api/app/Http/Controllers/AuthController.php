<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
            'is_delete' => 0,
        ];
        if (Auth::attempt($credentials, $data['remember'])) {
            $user = Auth::user();
            $tokenResult = $user->createToken('Login Token');
            if ($data['remember']) {
                $tokenResult->accessToken->expires_at = null;
            } else {
                $tokenResult->accessToken->expires_at = now()->addHours(1);
            }

            User::updateLoginInfo($user->id, $request->ip());
            $result['token'] = $tokenResult->plainTextToken;
            return $this->successResponse('Đăng nhập thành công.', $result);
        }

        return $this->errorResponse('Tài khoản hoặc mật khẩu không chính xác', Response::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->sendResponse([], 'Đăng xuất thành công.');
    }
}
