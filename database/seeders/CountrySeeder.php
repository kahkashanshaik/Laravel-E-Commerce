<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indiaStates = [
            'AP' => 'Andhra Pradesh',
            'KA' => 'Karnataka',
            'MP' => 'Madhya Pradesh',
            'RJ' => 'Rajasthan'
        ];

        $countries = [
            ['code' => 'geo', 'name' => 'Georgia', 'states' => null],
            ['code' => 'ind', 'name' => 'India', 'states' => null],
            ['code' => 'usa', 'name' => 'United States of America', 'states' => json_encode($indiaStates)],
            ['code' => 'ger', 'name' => 'Germany', 'states' => null],
        ];

        Country::insert($countries);
    }
}
