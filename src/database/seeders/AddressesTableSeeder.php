<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::create([
            'user_id' => 1,
            'zip' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => '新宿ビル101',
        ]);
    }
}
