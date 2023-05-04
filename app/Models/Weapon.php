<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Weapon extends Model
{
    use HasFactory;

    protected $table = "Weapons";

    protected $primaryKey = 'weaponId';


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');

        static::addGlobalScope('validWeapons', function (Builder $builder) {
            $builder->whereNotIn('Weapons.code', ['smokegrenade_projectile', 'PROP_PHYSICS', 'hegrenade', 'flashbang_projectile', 'flashbang']);
        });
    }

    public function games(): belongsTo
    {
        return $this->belongsTo(Game::class, 'game', 'code');
    }

    public function servers(): BelongsTo
    {
        return $this->belongsTo(Server::class, 'game', 'game');
    }

    public function weaponsHits(): HasMany
    {
        return $this->hasMany(PlayerWeaponHits::class, 'weapon', 'code');
    }

    public function weaponShots(): hasMany
    {
        return $this->hasMany(PlayerWeaponShot::class, 'weapon', 'code');
    }

}
