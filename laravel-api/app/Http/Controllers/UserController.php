<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            ->select('up.email', 'up.first_name', 'up.last_name', 'up.birthday', 'up.address', 'pr.name_en as city', 'di.name_en as district', 'wa.name_en as ward')
            ->join('provinces AS pr', 'pr.id', '=', 'up.province_id')
            ->join('districts AS di', 'di.id', '=', 'up.district_id')
            ->join('wards AS wa', 'wa.id', '=', 'up.ward_id')
            ->get();

        return response()->json([
            'data' => $profile
        ]);
    }
}
