<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    // Specify the table name if it's not the plural of the model name
    protected $table = 'districts';

    // Specify which attributes are mass assignable
    protected $fillable = [
        'name',
        // Add other relevant fields here
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code');
    }

    public static function getDistrictsByProvinceCode($provinceCode) {
        return self::where('province_code', $provinceCode)
            ->select('id', 'name_en as name', 'code_name', 'province_code')
            ->get();
    }
}
