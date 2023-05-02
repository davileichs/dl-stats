<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;


class Player extends Model
{
    use HasFactory;


    protected $table = "Players";


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('validPlayer', function (Builder $builder) {
            $builder->where('Players.hideranking', '=', 0);
        });
    }

    public function names(): HasMany
    {
        return $this->hasMany(PlayerName::class, 'playerId', 'playerId');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(PlayerHistory::class, 'playerId', 'playerId');
    }


    public function livestats(): hasOne
    {
        return $this->hasOne(Livestats::class, 'player_id', 'playerId');
    }

    public function weapons(): hasMany
    {
        return $this->hasMany(PlayerWeapon::class, 'playerId', 'playerId');
    }

    public function weaponShots(): hasMany
    {
        return $this->hasMany(PlayerWeaponShot::class, 'playerId', 'playerId');
    }

    public function games(): belongsTo
    {
        return $this->belongsTo(Game::class, 'game', 'code');
    }

    public function servers(): belongsTo
    {
        return $this->belongsTo(Server::class, 'game', 'game');
    }

    public function mapConnects(): hasMany
    {
        return $this->hasMany(PlayerMapConnect::class, 'playerId', 'playerId');
    }

    public function mapDisconnects(): hasMany
    {
        return $this->hasMany(PlayerMapDisconnect::class, 'playerId', 'playerId');
    }

    public function actions(): HasManyThrough
    {
        return $this->hasManyThrough(Action::class, PlayerAction::class, 'playerId', 'id', 'playerId', 'actionId');
    }

    protected function skill(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value),
        );
    }

    public function getSteamAttribute(): string
    {
        $steam = $this->hasOne(PlayerSteam::class, 'playerId', 'playerId')->first();

        return getSteamProfileId($steam->uniqueId);
    }

    public function getAvatarAttribute(): string
    {
        return getSteamAvatar($this->steam);
    }

    public function getNicknameAttribute()
    {
        return $this->lastName;
    }

    public function getNamesAttribute(): array
    {
        $names = $this->names()->where('name', '!=', $this->lastName)->where('connection_time', ">", 60);

        return $names->pluck('name')->toArray();
    }

    public function getSessionPointsAttribute(): array
    {
        $sessions = $this->sessions()->orderBy('eventTime', 'asc')->get();
        $array_sessions = $sessions->pluck('skill_change','eventTime')->toArray();

        $newKeysArray = [];
        foreach($array_sessions as $key => $session) {
            $newKey = substr($key,-5);
            $inverse = explode('-', $newKey);
            $newOrder = implode('-', array_reverse($inverse));
            $newKeysArray[$newOrder] = $session;
        }
        return $newKeysArray;
    }

    public function getSessionConnectionsAttribute(): array
    {
        $sessions = $this->sessions()->orderBy('eventTime', 'asc')->get();
        $array_sessions = $sessions->pluck('connection_time','eventTime')->toArray();

        $newKeysArray = [];
        foreach($array_sessions as $key => $session) {
            $newKey = substr($key,-5);
            $inverse = explode('-', $newKey);
            $newOrder = implode('-', array_reverse($inverse));
            $newKeysArray[$newOrder] = ceil($session/60);
        }
        return $newKeysArray;
    }


    public function getTimeAttribute(): string
    {
        return time2string($this->connection_time, ['d','h']);
    }

    public function getRankingAttribute()
    {
        return Player::select('playerId')
            ->whereRaw("skill > (SELECT skill FROM hlstats_Players WHERE playerId=? and game like ? LIMIT 1)",[$this->playerId, $this->game])
            ->where('game','LIKE',$this->game)
            ->count()+1;
    }

    public function getRewardsAttribute(): array
    {
        $rewards = $this->actions()
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

        return array(
            'zombie_points'     => number_format($zombiePoints),
            'zombie_percent'    => $percentZombie,
            'human_points'      => number_format($humanPoints),
            'human_percent'     => $percentHuman,
            'neutral_points'    => number_format($neutralPoints),
            'neutral_percent'   => $percentNeutral
        );
    }

    public function listActions()
    {
        return $this->actions()
            ->select([\DB::raw('SUM(reward_player) as points'), 'Actions.description', 'Actions.id', 'Events_PlayerActions.playerId', 'Actions.team'])
            ->groupBy(['Actions.id', 'Events_PlayerActions.playerId'])
            ->orderBy('points', 'desc')
            ->get();
    }

    public function listWeapons(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->weapons()
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

    public function getWeaponShotsAttribute(): array
    {
        $shots = $this->weaponShots()
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

    public function listMaps()
    {
        $connects = $this->mapConnects()->orderBy('eventTime', 'desc')->get()->toArray();
        $disconnects = $this->mapDisconnects()->orderBy('eventTime', 'desc')->get()->toArray();

        $con_Collection = new Collection($connects);
        $dis_Collection = new Collection($disconnects);

        $merged = $con_Collection->merge($dis_Collection)->sortBy('ipAddress')->sortBy('eventTime', SORT_REGULAR, true);

        return $merged;
    }

    public function getIsOnlineAttribute(): bool
    {
        return $this->livestats()->exists();
    }
}
