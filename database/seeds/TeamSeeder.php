<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teams')->insert([
            [
                'name' => 'Arsenal',
                'overall_rating' => 83.67
            ], [
                'name' => 'Chelsea',
                'overall_rating' => 84.33
            ], [
                'name' => 'Liverpool',
                'overall_rating' => 86.33
            ], [
                'name' => 'Manchester City',
                'overall_rating' => 87
            ]
        ]);
    }
}
