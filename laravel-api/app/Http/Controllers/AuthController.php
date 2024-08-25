<?php
namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $user = Auth::user();
        $profile = UserProfile::from('user_profiles AS up')
            ->where('up.user_id', $user['id'])
            ->select('us.email', 'up.first_name as firstName', 'up.last_name as lastName', 'up.birthday', 'up.address', 'pr.name_en as city', 'di.name_en as district', 'wa.name_en as ward')
            ->join('users AS us', 'us.id', '=', 'up.user_id')
            ->join('provinces AS pr', 'pr.id', '=', 'up.province_id')
            ->join('districts AS di', 'di.id', '=', 'up.district_id')
            ->join('wards AS wa', 'wa.id', '=', 'up.ward_id')
            ->first();

        return response()->json([
            'data' => $profile
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
