<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;

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
        $profile = UserProfile::getUserProfileById($user['id']);

        return response()->json([
            'data' => $profile
        ]);
    }
}
