<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'birthday',
        'gender_id',
        'address',
        'ward_id',
        'district_id',
        'province_id',
    ];

    public static function getUserProfileById($userId) {
        return self::from('user_profiles AS up')
            ->where('up.user_id', $userId)
            ->select('up.email', 'up.first_name', 'up.last_name', 'up.birthday', 'up.address', 'pr.name_en as city', 'di.name_en as district', 'wa.name_en as ward')
            ->join('provinces AS pr', 'pr.id', '=', 'up.province_id')
            ->join('districts AS di', 'di.id', '=', 'up.district_id')
            ->join('wards AS wa', 'wa.id', '=', 'up.ward_id')
            ->get();
    }
}
