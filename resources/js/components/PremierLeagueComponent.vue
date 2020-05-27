<template>
    <div class="md:px-32 py-8 w-full">
        <div class="flex">
            <div class="flex flex-wrap league-container">
                <div class="shadow overflow-hidden rounded border-b border-gray-200 mr-2 league-table-container">
                    <table class="min-w-full bg-white">
                        <thead class="text-white">
                        <tr class="bg-gray-500">
                            <th class="py-3" colspan="7">League Table</th>
                        </tr>
                        <tr class="bg-gray-300">
                            <th class="text-left font-semibold text-sm px-2 py-1">Teams</th>
                            <th class="uppercase font-semibold text-sm px-2 py-1">PTS</th>
                            <th class="uppercase font-semibold text-sm px-2 py-1">P</th>
                            <th class="uppercase font-semibold text-sm px-2 py-1">W</th>
                            <th class="uppercase font-semibold text-sm px-2 py-1">P</th>
                            <th class="uppercase font-semibold text-sm px-2 py-1">L</th>
                            <th class="uppercase font-semibold text-sm px-2 py-1">GD</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <tr v-for="team_state in teams_state">
                                <td class="text-left px-2 py-1">{{team_state['name']}}</td>
                                <td class="text-center px-2 py-1">{{team_state['pts']}}</td>
                                <td class="text-center px-2 py-1">{{team_state['p']}}</td>
                                <td class="text-center px-2 py-1">{{team_state['w']}}</td>
                                <td class="text-center px-2 py-1">{{team_state['d']}}</td>
                                <td class="text-center px-2 py-1">{{team_state['l']}}</td>
                                <td class="text-center px-2 py-1">{{team_state['gd']}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="shadow overflow-hidden rounded border-b border-gray-200 mr-2 match-result-container">
                    <table class="min-w-full bg-white">
                        <thead class="text-white">
                            <tr class="bg-gray-500">
                                <th class="py-3" colspan="3">Match Results</th>
                            </tr>
                            <tr class="bg-gray-300">
                                <th class="font-semibold text-sm px-2 py-1" colspan="3">
                                    <span v-show="current_week">{{current_week}}th</span>
                                    Week Match Result
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <tr v-for="match_result in match_results" v-show="current_week">
                                <td class="text-left w-100 px-2 py-1">{{match_result['home_team']}}</td>
                                <td class="text-center px-2 py-1">
                                    {{match_result['home_team_goals']}} - {{match_result['away_team_goals']}}
                                </td>
                                <td class="text-right w-100 px-2 py-1">{{match_result['away_team']}}</td>
                            </tr>
                            <tr v-show="!current_week">
                                <td class="text-center" colspan="3">League not started!</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="league-buttons flex justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded"
                            v-on:click="playAllMatches" v-show="current_week < 6" :disabled="doingMatch">
                        Play All
                    </button>

                    <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded"
                            v-on:click="playNextWeekMatches" v-show="current_week < 6" :disabled="doingMatch">
                        Next Week
                    </button>

                    <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded"
                            v-on:click="resetMatches" v-show="current_week == 6">
                        Reset Matches
                    </button>
                </div>
            </div>

            <div class="predict-table-container">
                <div class="shadow overflow-hidden rounded border-b border-gray-200 mr-2" v-show="current_week > 3">
                    <table class="min-w-full bg-white">
                        <thead class="text-white">
                            <tr class="bg-gray-300">
                                <th class="font-semibold text-sm px-2 py-1" colspan="2">{{current_week}}th Week Predictions for Championship</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <tr v-for="champion_predict in champion_predicts">
                                <td class="text-left w-150 px-2 py-1">{{champion_predict['name']}}</td>
                                <td class="text-right px-2 py-1">%{{champion_predict['prediction']}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [ 'data' ],
        data: function() {
            return {
                teams_state: [],
                match_results: [],
                champion_predicts: [],
                current_week: 0,
                doingMatch: false,
            }
        },
        mounted() {
            let self = this;
            self.initData(this.data);
        },
        methods: {
            playAllMatches: function() {
                let self = this;
                self.doingMatch = true;
                axios.post('/api/play_all_matches', { weekId: self.current_week })
                    .then(function (response) {
                        self.doingMatch = false;
                        self.initData(response.data);
                    })
                    .catch(function (error) {
                        self.doingMatch = false;
                        console.log(error);
                    })
            },
            playNextWeekMatches: function() {
                let self = this;
                self.doingMatch = true;
                axios.post('/api/play_next_week_matches', { weekId: self.current_week })
                    .then(function (response) {
                        self.doingMatch = false;
                        self.initData(response.data);
                    })
                    .catch(function (error) {
                        self.doingMatch = false;
                        console.log(error);
                    })
            },
            resetMatches: function() {
                let self = this;
                axios.post('/api/reset_matches')
                    .then(function (response) {
                        self.teams_state = [];
                        self.match_results = [];
                        self.current_week = 0;

                        self._.forEach(JSON.parse(response.data.teams_state), function(each_data) {
                            self.teams_state.push(each_data);
                        });
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
            },
            initData: function(data) {
                let self = this;

                self.teams_state = [];
                self.match_results = [];
                self.champion_predicts = [];

                self._.forEach(data.teams_state, function(team_state) {
                    self.teams_state.push(team_state);
                });
                self._.forEach(data.match_results, function(match_result) {
                    self.match_results.push(match_result);
                });
                self._.forEach(data.champion_predicts, function(champion_predict) {
                    self.champion_predicts.push(champion_predict);
                })

                self.teams_state = self._.orderBy(
                    self.teams_state,
                    ['pts', 'gd', 'name'],
                    ['desc', 'desc', 'asc']
                );
                self.champion_predicts = self._.orderBy(
                    self.champion_predicts,
                    ['prediction', 'name'],
                    ['desc', 'asc']
                );
                self.current_week = data.current_week;
            }
        }
    }
</script>
