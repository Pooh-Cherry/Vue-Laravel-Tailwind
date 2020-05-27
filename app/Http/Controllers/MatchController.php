<?php

namespace App\Http\Controllers;

use App\Team;
use App\LeagueWeek;
use App\Match;
use App\Models\TeamState;
use App\Traits\LeagueReport;

use Illuminate\Http\Request;

class MatchController extends Controller
{
    use LeagueReport;

    public function create(Request $request)
    {
        $weekId = $request->input('weekId');
        $weekId++;

        $match_results = $this->playWeekMatches($weekId);
        $teams_state = $this->getLeagueReport();
        $champion_predicts = $this->getChampionPredict($teams_state);

        return response()->json([
            'teams_state' => $teams_state,
            'match_results' => $match_results,
            'champion_predicts' => $champion_predicts,
            'current_week' => $weekId
        ]);
    }

    public function batchCreate(Request $request)
    {
        $weekId = $request->input('weekId');
        $weekId++;

        $match_results = array();

        while($weekId < 7) {
            $match_results = $this->playWeekMatches($weekId);
            $weekId++;
        }

        $teams_state = $this->getLeagueReport();
        $champion_predicts = $this->getChampionPredict($teams_state);

        return response()->json([
            'teams_state' => $teams_state,
            'match_results' => $match_results,
            'champion_predicts' => $champion_predicts,
            'current_week' => 6
        ]);
    }

    public function playWeekMatches($weekId) {
        $league_weeks = LeagueWeek::where('week', $weekId)->get();
        $match_results = array();

        foreach($league_weeks as $league_week) {
            $match_result = new \stdClass();
            $match_result->league_week_id = $league_week->id;
            $match_result->home_team_goals = rand(0, 5);
            $match_result->away_team_goals = rand(0, 5);

            Match::insert((array)$match_result);

            $match_result->home_team = Team::find($league_week->home_team_id)->name;
            $match_result->away_team = Team::find($league_week->away_team_id)->name;

            array_push($match_results, $match_result);
        }

        return $match_results;
    }

    public function batchDelete() {
        $match_results = Match::all();

        foreach($match_results as $match_result) {
            $match_result->delete();
        }

        $teams = Team::all();
        $teams_state = array();

        foreach($teams as $team) {
            $temp = new TeamState($team->name, 0, 0, 0, 0, 0, 0, $team->overall);
            array_push($teams_state, $temp);
        }

        return response()->json(['teams_state' => json_encode($teams_state)]);
    }
}
