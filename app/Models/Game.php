<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use App\Models\Scopes\ActiveScope;

class Game extends Model
{
    use HasFactory;


    protected $table = "Games";


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new ActiveScope);
    }

    public function servers(): hasMany
    {
        return $this->hasMany(Server::class, 'game', 'code');
    }


    public function clans(): hasMany
    {
        return $this->hasMany(Clan::class, 'game', 'code');
    }

    public function players(): hasMany
    {
        return $this->hasMany(Player::class, 'game', 'code');
    }


    public function getBestPlayerAttribute(): string
    {
        $player = $this->players()->orderBy('skill', 'DESC')->first();
        return $player->lastName ?? 'none';
    }


    public function getOnlinePlayersAttribute(): \StdClass
    {
        $active = 0;
        $max = 0;
        foreach($this->servers as $server) {
            $active += $server->act_players;
            $max += $server->max_players;
        }

        $count = new \stdClass;
        $count->active = $active;
        $count->max = $max;

        return $count;

    }


    public function getCountClansAttribute(): array
    {
        $active = 0;
        $max = 0;
        foreach($this->servers as $server) {
            $active += $server->act_players;
            $max += $server->max_players;
        }

        return compact('active', 'max');

    }

}
