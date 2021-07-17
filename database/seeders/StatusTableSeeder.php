<?php

namespace Database\Seeders;

use App\Models\Status;
use Database\Factories\StatusFactory;
use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::factory()->count(100)->create();
    }
}
