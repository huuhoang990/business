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

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code');
    }
}
