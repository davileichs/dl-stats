<?php

namespace App\Services;


use App\Models\Action;
use App\Models\Server;
use App\Models\ServerLoad;
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

    public static function get(?string $field = null): string|Server
    {
        if($field) {
            return self::$server->{$field};
        }
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

    public static function find(Server $server): self
    {
        if($server instanceof Server) {
            self::$server = $server;
        } else {
            self::$server = self::model()->where('serverId', $server)->first();
        }

        abort_if(!self::$server->exists, 404, 'No Server found');

        return new self();
    }

    public static function fromGame(string $game)
    {
        $server = self::model()->where('game' , $game)->first();
        self::$game = $game;
        return self::find($server);
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
                    $teams['humans']->push($row);
                    break;
                case 'TERRORIST':
                    $teams['zombies']->push($row);
                    break;
                default:
                    $teams['spectator']->push($row);
                    break;
            }
        }
        $teams->map(function ($team) {
            return $team->sortByDesc('skill_change');
        });

        return $teams;
    }

    public function statisticsDay(string $day = '') {
        $periodTimestamp = Carbon::now()->subDay()->timestamp;
        $reduce = 1;

        return $this->getServerLoad($periodTimestamp, $reduce);
    }

    public function statisticsWeek(string $week = '') {
        $periodTimestamp = Carbon::now()->subWeek()->timestamp;
        $reduce = 2;

        return $this->getServerLoad($periodTimestamp, $reduce);
    }

    public function statisticsMonth(string $month = '') {
        $periodTimestamp = Carbon::now()->subMonth()->timestamp;
        $reduce = 5;

        return $this->getServerLoad($periodTimestamp, $reduce);
    }

    public function statisticsYear(string $year = '') {
        $periodTimestamp = Carbon::now()->subYear()->timestamp;
        $reduce = 20;

        return $this->getServerLoad($periodTimestamp, $reduce);
    }


    protected function getServerLoad(?int $timestampInit = null, ?int $reduce = 1, ?int $timestampEnd = null): array
    {
        $timestampEnd = Carbon::now()->timestamp;

        $conn = config('database.active_stats');
        $loads = \DB::connection($conn)->select("
                SELECT * FROM
            ( SELECT @row := @row +1 AS rownum, `timestamp`, act_players, map, server_id
                FROM ( SELECT @row :=0) r, hlstats_server_load
            ) ranked
        where `server_id` = ?
        and rownum % ? = 0
        and `timestamp` >= ? and `timestamp` <= ? order by `timestamp` asc", [self::get('serverId'), $reduce, $timestampInit, $timestampEnd]);

        $stats = [];
        $act_players = 0;

        foreach($loads as $k=>$load) {
            $day = Carbon::createFromTimestamp($load->timestamp)->format('d-m');
            $hour = ($reduce<=2) ? Carbon::createFromTimestamp($load->timestamp)->format('H:s') : '';
            $map = ($reduce==1) ? $load->map : '';

            if($k % $reduce == 0 && $k > 0) {
                $act_players = round($act_players/$reduce);

                $stats[] = array(
                    'day'  => $day,
                    'hour' => $hour,
                    'act_players' => $act_players,
                    'map' => $map
                );
                $act_players = $load->act_players;

            } else {
                $act_players += $load->act_players;
            }

        }
        return $stats;
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

    public static function mapUsage(?string $date = null): array
    {

        $server = self::$server;
        if(!$date) {
            $date = Carbon::now()->toDateString();
        }

        $rows = $server->weaponsHits()
            ->with('players')
            ->whereDate('eventTime', $date)
            ->orderBy('Events_Statsme.eventTime', 'asc')
            ->get();

        $list = array();
        $count = 1;
        foreach($rows as $k=>$row) {

            if($k == 0 || strcmp($rows[($k-1)]->map, $row->map) !== 0) {
                $newKey = substr($row->eventTime, -8, 5);
                $list[$newKey] = ['map' => $row->map, 'players' => array()];
            }

            if ($row->players()->exists()) {
                if(empty($list[$newKey]['players'][$row->playerId])) {
                    $list[$newKey]['players'][$row->playerId] = (object)([
                        'playerId'  => $row->playerId,
                        'nickname'  => $row->players()->first()->nickname ?? '',
                        'shots'     => $row->shots,
                        'hits'      => $row->hits,
                        'damage'    => $row->damage,
                    ]);
                } else {
                    $list[$newKey]['players'][$row->playerId]->shots += $row->shots;
                    $list[$newKey]['players'][$row->playerId]->hits += $row->hits;
                    $list[$newKey]['players'][$row->playerId]->damage += $row->damage;
                }
            }


        }
        foreach($list as &$key) {
                $key['players'] =  Collection::make($key['players'])->sortByDesc('damage');
        }
       return (array_reverse($list));
    }

    public function players()
    {
        return PlayerService::fromGame(self::$game);
    }


}
