<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeagueWeek extends Model
{
    protected $table = 'league_weeks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'week', 'home_team_id', 'away_team_id'
    ];
}
