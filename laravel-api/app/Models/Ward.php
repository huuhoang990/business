<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    // Specify the table name if it's not the plural of the model name
    protected $table = 'wards';

    // Specify which attributes are mass assignable
    protected $fillable = [
        'name',
        // Add other relevant fields here
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public static function getWardsByDistrictCode($districtCode)
    {
        return self::select('id', 'name_en as name', 'code_name', 'district_code')->where('district_code', $districtCode)->get();
    }
}
