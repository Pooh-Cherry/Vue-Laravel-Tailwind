<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeagueWeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('league_weeks')->insert([
            [
                'week' => 1,
                'home_team_id' => 1,
                'away_team_id' => 2
            ], [
                'week' => 1,
                'home_team_id' => 3,
                'away_team_id' => 4
            ], [
                'week' => 2,
                'home_team_id' => 1,
                'away_team_id' => 3
            ], [
                'week' => 2,
                'home_team_id' => 2,
                'away_team_id' => 4
            ], [
                'week' => 3,
                'home_team_id' => 1,
                'away_team_id' => 4
            ], [
                'week' => 3,
                'home_team_id' => 2,
                'away_team_id' => 3
            ], [
                'week' => 4,
                'home_team_id' => 2,
                'away_team_id' => 1
            ], [
                'week' => 4,
                'home_team_id' => 4,
                'away_team_id' => 3
            ], [
                'week' => 5,
                'home_team_id' => 3,
                'away_team_id' => 1
            ], [
                'week' => 5,
                'home_team_id' => 4,
                'away_team_id' => 2
            ], [
                'week' => 6,
                'home_team_id' => 4,
                'away_team_id' => 1
            ], [
                'week' => 6,
                'home_team_id' => 3,
                'away_team_id' => 2
            ]
        ]);
    }
}
