<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class VNUnitsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to your SQL file
        $path = database_path('seeders/ImportData_vn_units.sql');
        
        // Check if the file exists
        if (File::exists($path)) {
            // Read the SQL file
            $sql = File::get($path);

            // Execute the SQL statements
            DB::unprepared($sql);
        } else {
            // Handle the case where the file does not exist
            $this->command->error("SQL file not found: {$path}");
        }
    }
}
