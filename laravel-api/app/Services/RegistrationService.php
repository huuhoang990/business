<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    /**
     * Register a new user
     *
     * @param array $data
     * @return array
     */
    public function register(array $data)
    {
        DB::beginTransaction();

        try {
            // Create a new user
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Create a new user profile
            UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'birthday' => $data['birthday'],
                'gender_id' => $data['genderId'],
                'address' => $data['street'],
                'ward_id' => $data['wardId'],
                'district_id' => $data['districtId'],
                'province_id' => $data['provinceId'],
            ]);

            DB::commit();

            return ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
