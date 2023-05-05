<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Map extends Model
{
    use HasFactory;


    protected $table = "Maps_Counts";

    protected $primaryKey = 'rowId';


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(PlayerName::class, 'playerId', 'playerId');
    }

    public function games(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game', 'code');
    }

    public function servers(): HasOneThrough
    {
        return $this->HasOneThrough(Server::class, Game::class, 'code', 'game', 'game', 'code');
    }

    public function weaponsHits(): HasMany
    {
        return $this->hasMany(PlayerWeaponHits::class, 'map', 'map');
    }

    public function weaponShots(): hasMany
    {
        return $this->hasMany(PlayerWeaponShot::class, 'map', 'map');
    }

    public function entries(): hasMany
    {
        return $this->hasMany(EventsEntry::class, 'map', 'map');
    }

    // public function getPopularityAttribute(): int
    // {
    //     return $this->entries()->count();
    // }

}
