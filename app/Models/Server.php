<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function getTimeAttribute(): string
    {
        return (time() - $this->connected);
    }

    public function getNumberPlayersAttribute(): string
    {
        $act_players = $this->act_players;
        $max_players = $this->max_players;
        if ($this->max_players == 65) {
            --$max_players;
        }

        return ( str_pad($act_players, 2, '0', STR_PAD_LEFT) . ' | ' . $max_players);

    }

    public function getRoundAttribute(): string
    {
        return $this->map_ct_wins . ':' .$this->map_ts_wins;
    }
}
