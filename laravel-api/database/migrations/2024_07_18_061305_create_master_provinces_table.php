<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         // Path to your SQL files inside the Laravel project directory
        $sqlFiles = [
            base_path('database\sql\CreateTables_vn_units.sql'), // Adjust this path
            base_path('database\sql\ImportData_vn_units.sql')    // Adjust this path
        ];

        foreach ($sqlFiles as $file) {
            if (File::exists($file)) {
                $sql = File::get($file);
                DB::unprepared($sql);
            } else {
                throw new \Exception("SQL file does not exist: " . $file);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_provinces');
    }
};
