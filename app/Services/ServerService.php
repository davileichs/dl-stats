<?php

namespace App\Services;


use App\Models\Action;
use App\Models\Server;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ServerService {


    protected static Server $server;
    protected static string $game;


    protected static function modelSimple(): Builder
    {
        return Server::select(['serverId', 'name', 'act_players', 'max_players', 'publicaddress', 'act_map', 'map_started']);
    }

    protected static function model(): Builder
    {
        return Server::select('*');
    }

    public static function get(): Server
    {
        return self::$server;
    }

    public static function list(?string $search = null): Collection
    {
        $servers = self::modelSimple();
        if ($search) {
            $servers->search('name', $search);
        }
        return $servers->get();
    }

    public static function set(Server $server): self
    {
        try {
            if(!$server->exists) {
                throw new \Exception("No server found", 1);
            }
            self::$server = $server;

        } catch(\Exception $e) {
            abort(404);
        }

        return new self();
    }

    public static function fromGame(string $game)
    {
        $server = self::model()->where('game' , $game)->first();
        self::$game = $game;
        return self::set($server);
    }

    public static function livestatsByTeam(): ?Collection
    {
        $teams = new Collection(['humans' => new Collection(), 'zombies' => new Collection(), 'spectator' => new Collection()]);

        if (empty(self::$server)) {
            return $teams;
        }
        $livestats = self::$server->livestats()->orderBy('skill_change', 'desc')->get();

        foreach($livestats as $row) {
            switch($row->team) {
                case 'CT':
                case 'TERRORIST':
                    $teams['humans']->push($row);
                    break;
                case 'Spectator':
                    $teams['spectator']->push($row);
                    break;
                default:
                    $teams['zombies']->push($row);
                    break;
            }
        }
        $teams->map(function ($team) {
            return $team->sortByDesc('skill_change');
        });

        return $teams;
    }


    protected function getTopFrom(array $codes)
    {
        return Action::select([
            'Players.playerId',
            'Players.lastName',
            \DB::raw('SUM(reward_player) as points')
            ])
        ->join('Events_PlayerActions', 'Events_PlayerActions.actionId', 'Actions.id')
        ->join('Players', 'Players.playerId', 'Events_PlayerActions.playerId')
        ->groupBy('Players.playerId')
        ->whereIn('code', $codes)
        ->where('Players.hideranking', 0)
        ->where('Players.game', self::get()->game)
        ->where('eventTime', '>=', Carbon::now()->subMonth())
        ->groupBy(['Players.playerId'])
        ->orderBy('points', 'desc')
        ->limit(10);
    }

    public function topTriggerPlayers()
    {
        return $this->getTopFrom(['trigger', 'trigger_hh'])->get();
    }

    public function topDefenderPlayers()
    {
        return $this->getTopFrom([
            'ze_defender_third', 'ze_defender_second', 'ze_defender_first',
            'ze_defender_first_hh', 'ze_defender_second_hh', 'ze_defender_third_hh'
        ])->get();
    }

    public function topWinnerExtremePlayers()
    {
        return $this->getTopFrom(['event_win_4', 'ze_h_win_3_hh'])->get();
    }

    public function topBossDamagePlayers()
    {
        return $this->getTopFrom([
            'ze_boss_damage_first', 'ze_boss_damage_second', 'ze_boss_damage_third',
            'ze_boss_damage_first_hh', 'ze_boss_damage_second_hh', 'ze_boss_damage_third_hh'
        ])->get();
    }

    public function topSoloPlayers()
    {
        return $this->getTopFrom(['ze_h_win_solo', 'ze_h_win_solo_hh'])->get();
    }

    public function topZombieDamagePlayers()
    {
        return $this->getTopFrom(['ze_damage_zombie', 'ze_damage_zombie_hh'])->get();
    }

    public function topMotherZombiePlayers()
    {
        return $this->getTopFrom([
            'ze_m_win_0','ze_m_kill_streak_12', 'ze_m_kill_streak_11', 'ze_m_kill_streak_10',
            'ze_m_kill_streak_09', 'ze_m_kill_streak_08', 'ze_m_kill_streak_07', 'ze_m_kill_streak_06',
            'ze_m_kill_streak_05', 'ze_m_kill_streak_04', 'ze_m_kill_streak_03', 'ze_m_kill_streak_02',
            'ze_m_win_0_hh', 'ze_m_kill_streak_12_hh', 'ze_m_kill_streak_11_hh', 'ze_m_kill_streak_10_hh',
            'ze_m_kill_streak_09_hh', 'ze_m_kill_streak_08_hh', 'ze_m_kill_streak_07_hh', 'ze_m_kill_streak_06_hh',
            'ze_m_kill_streak_05_hh', 'ze_m_kill_streak_04_hh', 'ze_m_kill_streak_03_hh', 'ze_m_kill_streak_02_hh',
        ])->get();
    }

    public function topInfectorPlayers()
    {
        return $this->getTopFrom([
            'ze_infector_first', 'ze_infector_second', 'ze_infector_third',
            'ze_infector_first_hh', 'ze_infector_second_hh', 'ze_infector_third_hh',
            ])->get();
    }

    public function players()
    {
        return PlayerService::fromGame(self::$game);
    }


}
