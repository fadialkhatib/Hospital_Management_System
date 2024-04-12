<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Session;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
        Session::create([
            'MACadress'=>'12345',
            'sessionKey'=>''
        ]);
    }
}
