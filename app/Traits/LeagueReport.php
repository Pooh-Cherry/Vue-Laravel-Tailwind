<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\TeamState;

trait LeagueReport {

    public function getLeagueReport() {
        $teams = DB::table('teams')->get();
        $teams_state = array();

        foreach ($teams as $team) {
            $team_state = new TeamState($team->name, 0, 0, 0, 0, 0, 0, $team->overall_rating);

            $team_home_league_weeks = DB::table('league_weeks')
                ->where('home_team_id', $team->id)
                ->get();
            $team_away_league_weeks = DB::table('league_weeks')
                ->where('away_team_id', $team->id)
                ->get();

            $team_home_league_weeks_ids = [];
            $team_away_league_weeks_ids = [];
            foreach ($team_home_league_weeks as $team_home_league_week) {
                array_push($team_home_league_weeks_ids, $team_home_league_week->id);
            }

            foreach ($team_away_league_weeks as $team_away_league_week) {
                array_push($team_away_league_weeks_ids, $team_away_league_week->id);
            }

            $team_home_matches = DB::table('matches')->whereIn('league_week_id', $team_home_league_weeks_ids)->get();
            $team_away_matches = DB::table('matches')->whereIn('league_week_id', $team_away_league_weeks_ids)->get();

            foreach($team_home_matches as $team_home_match) {
                $team_state = $this->recordMatch($team_state, $team_home_match, true);
            }

            foreach($team_away_matches as $team_away_match) {
                $team_state = $this->recordMatch($team_state, $team_home_match, false);
            }

            array_push($teams_state, $team_state);
        }

        return $teams_state;
    }

    public function recordMatch($team_state, $match, $flag) {
        $team_state->p++;

        if ($match->home_team_goals == $match->away_team_goals) {
            $team_state->pts += 1;
            $team_state->d += 1;
        } else {
           if ($flag) {
               if ($match->home_team_goals > $match->away_team_goals) {
                   $team_state->pts += 3;
                   $team_state->w += 1;
               } else
                   $team_state->l += 1;

               $team_state->gd = $team_state->gd + $match->home_team_goals - $match->away_team_goals;
           } else {
               if ($match->home_team_goals < $match->away_team_goals) {
                   $team_state->pts += 3;
                   $team_state->w += 1;
               } else
                   $team_state->l += 1;

               $team_state->gd = $team_state->gd + $match->away_team_goals - $match->home_team_goals;
           }
        }

        return $team_state;
    }

    public function getChampionPredict($teams_state) {
        $total_strength = 0;
        $champion_predict = array();

        foreach($teams_state as $team_state) {
            $total_strength += $team_state->pts * $team_state->strength;
        }

        foreach($teams_state as $team_state) {
            $temp = new \stdClass();
            $temp->name = $team_state->name;
            $temp->prediction = $team_state->pts ? intval($team_state->pts * $team_state->strength / $total_strength * 100) : 0;

            array_push($champion_predict, $temp);
        }

        return $champion_predict;
    }
}
