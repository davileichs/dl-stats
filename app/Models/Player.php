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

    protected $primaryKey = 'playerId';


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

    public function weaponsHits(): hasMany
    {
        return $this->hasMany(PlayerWeaponHits::class, 'playerId', 'playerId');
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

    public function steam(): hasOne
    {
        return $this->hasOne(PlayerSteam::class, 'playerId', 'playerId');
    }

    public function playerActions(): hasMany
    {
        return $this->hasMany(PlayerAction::class, 'playerId', 'playerId');
    }

    public function entries(): hasMany
    {
        return $this->hasMany(EventsEntry::class, 'playerId', 'playerId');
    }

    protected function skill(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value),
        );
    }

    protected function lastEvent(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => timeago2string($value, 3),
        );
    }

    protected function createdate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => timeago2string($value),
        );
    }

    protected function connectionTime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => time2string($value, ['d','h','m']),
        );
    }

    public function getSteamIdAttribute(): string
    {
        return getSteamProfileId($this->steam()->first()->uniqueId);
    }

    public function getAvatarAttribute(): string
    {
        return getSteamAvatar($this->steam_id);
    }

    public function getNicknameAttribute()
    {
        return $this->lastName;
    }

}
