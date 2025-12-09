<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;

class UpdateUserNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::get()->each(function ($user) {
            $fake = Faker::create();
            $user->first_name = $fake->firstName() ;
            $user->last_name =  $fake->lastName();
            $user->save();
        });
    }
}
