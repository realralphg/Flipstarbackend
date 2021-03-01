<?php

use Illuminate\Database\Seeder;
use App\User;
use App\UserWallet;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' =>  Hash::make('secret'),
        ]);
        UserWallet::create([
            'user_id' => 1,
            'amount' => 5000,
        ]);
    }
}
