<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(100)->create();

        $user = User::find(1);
        $user->email = 'zhangsan@qq.com';
        $user->name = 'zhangsan';
        $user->password = bcrypt('zhangsan');
        $user->is_admin = true;
        $user->is_active = true;
        $user->save();
    }
}
