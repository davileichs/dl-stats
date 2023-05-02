<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Type\Decimal;

class Server extends Model
{
    use HasFactory;

    protected $table = "Servers";

    protected $primaryKey = 'serverId';


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('players', '>', '0');
    }

    public function games(): hasOne
    {
        return $this->hasOne(Game::class, 'code', 'game');
    }

    public function players(): HasManyThrough
    {
        return $this->hasManyThrough(Player::class, Game::class, 'code', 'game' , 'game', 'code');
    }

    public function livestats(): hasOne
    {
        return $this->hasOne(Livestats::class, 'server_id', 'serverId');
    }

    public function actions(): HasManyThrough
    {
        return $this->hasManyThrough(Action::class, PlayerAction::class, 'serverId', 'id', 'serverId', 'actionId');
    }


    public function connectedPlayers(): HasManyThrough
    {
        return $this->hasManyThrough(Player::class, Livestats::class, 'server_id', 'playerId' , 'serverId', 'player_id');
    }


    public function getPlayedAttribute(): string
    {
        $ts1 = ($this->map_started);
        $ts2 = time();
        $seconds_diff = $ts2 - $ts1;
        return time2string($seconds_diff, ['h', 'm', 's']);
    }

    public function getHsKAttribute(): float
    {
        if($this->kills > 0) {
            return round($this->headshots / $this->kills, 2);
        }
        return  0;
    }

    public function getTimeAttribute()
    {
        return (time() - $this->connected);
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
        ->where('Players.game', $this->game)
        ->where('eventTime', '>=', Carbon::now()->subMonth())
        ->groupBy(['Players.playerId'])
        ->orderBy('points', 'desc')
        ->limit(10);
    }


    public function getLivestats()
    {
        $livestats = $this->livestats()->orderBy('skill_change', 'desc')->get();

        $teams = new Collection(['humans' => new Collection(), 'zombies' => new Collection(), 'spectator' => new Collection()]);
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


    public function topTriggerPlayers()
    {
        return $this->getTopFrom(['trigger', 'trigger_hh'])->get();
    }

    public function topDefenderPlayers()
    {
        return $this->getTopFrom([
            'ze_defender_third',
            'ze_defender_second',
            'ze_defender_first',
            'ze_defender_first_hh',
            'ze_defender_second_hh',
            'ze_defender_third_hh'
        ])->get();
    }

    public function topWinnerExtremePlayers()
    {
        return $this->getTopFrom(['event_win_4', 'ze_h_win_3_hh'])->get();
    }

    public function topBossDamagePlayers()
    {
        return $this->getTopFrom([
            'ze_boss_damage_first',
            'ze_boss_damage_second',
            'ze_boss_damage_third',
            'ze_boss_damage_first_hh',
            'ze_boss_damage_second_hh',
            'ze_boss_damage_third_hh'
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
            'ze_m_win_0',
            'ze_m_kill_streak_12',
            'ze_m_kill_streak_11',
            'ze_m_kill_streak_10',
            'ze_m_kill_streak_09',
            'ze_m_kill_streak_08',
            'ze_m_kill_streak_07',
            'ze_m_kill_streak_06',
            'ze_m_kill_streak_05',
            'ze_m_kill_streak_04',
            'ze_m_kill_streak_03',
            'ze_m_kill_streak_02',
            'ze_m_win_0_hh',
            'ze_m_kill_streak_12_hh',
            'ze_m_kill_streak_11_hh',
            'ze_m_kill_streak_10_hh',
            'ze_m_kill_streak_09_hh',
            'ze_m_kill_streak_08_hh',
            'ze_m_kill_streak_07_hh',
            'ze_m_kill_streak_06_hh',
            'ze_m_kill_streak_05_hh',
            'ze_m_kill_streak_04_hh',
            'ze_m_kill_streak_03_hh',
            'ze_m_kill_streak_02_hh',
        ])->get();
    }

    public function topInfectorPlayers()
    {
        return $this->getTopFrom([
            'ze_infector_first',
            'ze_infector_second',
            'ze_infector_third',
            'ze_infector_first_hh',
            'ze_infector_second_hh',
            'ze_infector_third_hh',
            ])->get();
    }

}
