<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserTableSeeder::class);

        $this->call(StatusTableSeeder::class);

        $this->call(FollowerTableSeeder::class);

        Model::reguard();
    }
}
