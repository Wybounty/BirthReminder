<?php

namespace Database\Seeders;

use App\Models\Birthday;
use Illuminate\Database\Seeder;

class BirthdaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Birthday::factory(10)->create();
    }
}
