<?php

namespace App\Http\Controllers;
use App\Team;
use App\LeagueWeek;
use App\Match;
use App\Models\TeamState;
use App\Traits\LeagueReport;

class HomeController extends Controller
{
    use LeagueReport;

    public function index()
    {
        $matches = Match::all();
        $weekId = count($matches) / 2;

        $match_results = array();

        if($weekId) {
            $last_week_matches = Match::orderBy('league_week_id', 'desc')->take(2)->get();

            foreach($last_week_matches as $last_week_match) {
                $match_result = new \stdClass();
                $match_result->home_team_goals = $last_week_match->home_team_goals;
                $match_result->away_team_goals = $last_week_match->away_team_goals;

                $league_week = LeagueWeek::find($last_week_match->league_week_id);
                $home_team = Team::find($league_week->home_team_id);
                $away_team = Team::find($league_week->away_team_id);

                $match_result->home_team = $home_team->name;
                $match_result->away_team = $away_team->name;

                array_push($match_results, $match_result);
            }
        }

        $return_data = new \stdClass();
        $return_data->teams_state = $this->getLeagueReport();
        $return_data->match_results = $match_results;
        $return_data->champion_predicts = $this->getChampionPredict($return_data->teams_state);
        $return_data->current_week = $weekId;

        return view('welcome', ['data' => json_encode($return_data)]);
    }
}
