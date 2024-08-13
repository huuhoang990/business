<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    // Specify the table name if it's not the plural of the model name
    protected $table = 'provinces';

    // Specify which attributes are mass assignable
    protected $fillable = [
        'name',
        // Add other relevant fields here
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_code');
    }
}