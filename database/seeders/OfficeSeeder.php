<?php

namespace Database\Seeders;

use App\Models\Offices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Offices::insert([
            ['office_name' => 'Schools Division Superintent'],
            ['office_name' => 'Assistant School Division Superintent'],
            ['office_name' => 'Commission on Audit'],
            ['office_name' => 'Accounting'],
            ['office_name' => 'Budget'],
            ['office_name' => 'Legal'],
            ['office_name' => 'Information and Communication Technology'],
            ['office_name' => 'Payroll'],
            ['office_name' => 'Cash'],
            ['office_name' => 'Personnel'],
            ['office_name' => 'Human Resource'],
            ['office_name' => 'Records'],
            ['office_name' => 'Property and Supply'],
            ['office_name' => 'BAC'],
            ['office_name' => 'Curriculum Implementation Division'],
            ['office_name' => 'SGOD Unit 1'],
            ['office_name' => 'SGOD Unit 2'],
            ['office_name' => 'Recieving/Releasing'],
            ['office_name' => 'Decision Maker'],
            ['office_name' => 'Medical/Dental'],
            ['office_name' => 'Admin Office'],
        ]);
    }
}



