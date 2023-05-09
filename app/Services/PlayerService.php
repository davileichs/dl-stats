<?php

namespace App\Services;


use App\Models\Action;
use App\Models\Player;
use App\Models\PlayerHistory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PlayerService {


    protected static ?Player $player;
    protected static string $game;


    protected static function modelSimple(): Builder
    {
        return Player::select(['playerId', 'lastName', 'connection_time', 'skill', 'hideRanking', 'activity', 'game']);
    }

    protected static function model(): Builder
    {
        return Player::select('*');
    }

    public static function get(?string $field = null): String|Player
    {
        abort_if(!isset(self::$player), 404, 'No Player found');
        abort_if(!self::$player->exists, 404, 'No Player found');

        if($field) {
            return self::$player->{$field};
        }

        return self::$player;
    }

    public static function list(?string $search = null)
    {
        $players = self::modelSimple();

        if ($search) {
            $players = $players->search('lastName', $search);
        }
        if(!empty(self::$game)) {
            $players = $players->where('game', self::$game)
                ->orderByDesc('skill')
                ->with('games')
                ->paginate(50);
        }
        return $players;
    }


    public static function find(int|Player $player): Self
    {
        if($player instanceof Player) {
            self::$player = $player;
        } else {
            self::$player = self::model()->find($player) ?? null;
        }
        abort_if((!self::$player || !self::$player->exists), 404, 'No Player found');

        return new self();
    }

    public static function fromGame(string $game): self
    {
        self::$game = $game;
        return new self();
    }

    public static function alsoAsName(): array
    {
        $player = self::get();
        $names = $player->names()->where('name', '!=', $player->nickname)->where('connection_time', ">", 1000);
        return $names->pluck('connection_time','name')->toArray();
    }

    public static function server()
    {
        $player = self::get();
        return ServerService::find($player->servers()->first());
    }

    public static function sessionPoints(): array
    {
        $player = self::get();
        $sessions = $player->sessions()->orderBy('eventTime', 'asc')->get();
        $array_sessions = $sessions->pluck('skill_change','eventTime')->toArray();

        return self::orderSessionArray($array_sessions);
    }

    public static function sessionConnections(): array
    {
        $player = self::get();
        $sessions = $player->sessions()->select(['eventTime', \DB::raw(' round(connection_time/60) as connection_time')])->orderBy('eventTime', 'asc')->get();
        $array_sessions = $sessions->pluck('connection_time','eventTime')->toArray();

        return self::orderSessionArray($array_sessions);
    }

    protected static function orderSessionArray(array $array_sessions)
    {
        $newKeysArray = [];
        foreach($array_sessions as $key => $session) {
            $newKey = substr($key,-5);
            $inverse = explode('-', $newKey);
            $newOrder = implode('-', array_reverse($inverse));
            $newKeysArray[$newOrder] = $session;
        }
        return $newKeysArray;
    }

    public static function ranking()
    {
        $player = self::get();
        return Player::select('playerId')
            ->whereRaw("skill > (SELECT skill FROM hlstats_Players WHERE playerId=? and game like ? LIMIT 1)",[$player->playerId, $player->game])
            ->where('game','LIKE',$player->game)
            ->count()+1;
    }

    public static function rewards(): array
    {
        $player = self::get();
        $rewards = $player->actions()
        ->select([\DB::raw('SUM(reward_player) as points'), 'Actions.id', 'Events_PlayerActions.playerId', 'Actions.team'])
        ->groupBy(['Actions.id', 'Events_PlayerActions.playerId'])
        ->orderBy('points', 'desc')
        ->get();

        $zombiePoints = 0;
        $humanPoints = 0;
        $neutralPoints = 0;
        $total = 0;
        foreach($rewards as $reward) {
            if($reward['team'] == 'HUMAN') {
                $humanPoints += $reward['points'];
            } elseif($reward['team'] == 'ZOMBIE') {
                $zombiePoints += $reward['points'];
            }
            else {
                $neutralPoints += $reward['points'];
            }
            $total += $reward['points'];
        }

        $percentZombie = percent($zombiePoints, $total);
        $percentHuman = percent($humanPoints, $total);
        $percentNeutral = percent($neutralPoints, $total, 'ceil');

        return [
            'human' => ['points' => number_format($humanPoints), 'percent' => $percentHuman],
            'neutral' => ['points' => number_format($neutralPoints), 'percent' => $percentNeutral],
            'zombie' => ['points' => number_format($zombiePoints), 'percent' => $percentZombie],
        ];
    }

    public static function listActions()
    {
        $player = self::get();
        return $player->actions()
            ->select([\DB::raw('SUM(reward_player) as points'), 'Actions.description', 'Actions.id', 'Events_PlayerActions.playerId', 'Actions.team'])
            ->groupBy(['Actions.id', 'Events_PlayerActions.playerId'])
            ->orderBy('points', 'desc')
            ->get();
    }

    public static function listWeaponsHits(): \Illuminate\Database\Eloquent\Collection
    {
        $player = self::get();
        return $player->weaponsHits()
            ->select([
                \DB::raw('SUM(hits) as hits'),
                \DB::raw('SUM(damage) as damage'),
                \DB::raw('SUM(shots) as shots'),
                'Events_Statsme.weapon',
                'Events_Statsme.playerId'
            ])
            ->groupBy(['Events_Statsme.weapon', 'Events_Statsme.playerId'])
            ->orderBy('damage', 'desc')
            ->get();
    }

    public static function weaponsShots(): array
    {
        $player = self::get();
        $shots = $player->weaponShots()
            ->select([
                \DB::raw('SUM(head) as head'),
                \DB::raw('SUM(chest) as chest'),
                \DB::raw('SUM(stomach) as stomach'),
                \DB::raw('SUM(leftarm) as leftarm'),
                \DB::raw('SUM(rightarm) as rightarm'),
                \DB::raw('SUM(leftleg) as leftleg'),
                \DB::raw('SUM(rightleg) as rightleg'),
                'Events_Statsme2.playerId',
            ])
            ->groupBy(['Events_Statsme2.playerId'])
            ->first();

            return array(
                'head'      => $shots['head'] ?? 0,
                'rightarm'  => $shots['rightarm'] ?? 0,
                'chest'     => $shots['chest'] ?? 0,
                'rightleg'  => $shots['rightleg'] ?? 0,
                'leftleg'   => $shots['leftleg'] ?? 0,
                'stomach'   => $shots['stomach'] ?? 0,
                'leftarm'   => $shots['leftarm'] ?? 0,
            );
    }

    public static function listMaps()
    {
        $player = self::get();
        $connects = $player->mapConnects()->orderBy('eventTime', 'desc')->get()->toArray();
        $disconnects = $player->mapDisconnects()->orderBy('eventTime', 'desc')->get()->toArray();

        $con_Collection = new Collection($connects);
        $dis_Collection = new Collection($disconnects);

        $merged = $con_Collection->merge($dis_Collection)->sortBy('ipAddress')->sortBy('eventTime', SORT_REGULAR, true);

        return $merged;
    }

    public static function getIsOnlineAttribute(): bool
    {
        $player = self::get();
        return $player->livestats()->exists();
    }

    public static function history(string $game): array
    {
        $history = PlayerHistory::select([
            'eventTime',
            \DB::raw('round(SUM(connection_time)/60) as connection_time')
        ])
        ->where('game', $game)
        ->groupBy('eventTime')
        ->orderby('eventTime', 'asc')
        ->get();

        $array_sessions = $history->pluck('connection_time','eventTime')->toArray();

        return self::orderSessionArray($array_sessions);
    }

    public static function mapsPlayed(?string $date = null): array
    {
        if(!$date) {
            $date = Carbon::now()->toDateString();
        }
        $rows = self::$player->playerActions()
            ->select([
                'Players.lastName as nickname',
                'Players.playerId',
                'Events_PlayerActions.*',
                'Actions.code'
            ])
            ->join('Players', 'Players.playerId', 'Events_PlayerActions.playerId')
            ->join('Actions', 'Actions.id', 'Events_PlayerActions.actionId')
            ->where('hideRanking', 0)
            ->whereDate('eventTime', $date)
            ->orderBy('Events_PlayerActions.eventTime', 'asc')
            ->get();

        return MapService::playersOnMap($rows, false);
    }


}
