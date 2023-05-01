<?php

namespace App\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clan extends Model
{
    use HasFactory;


    protected $table = "Clans";
    protected $connection = 'mysql_stats';
    protected $primaryKey = 'clanId';


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('hidden', '0');
    }


    public function players(): HasMany
    {
        return $this->hasMany(Player::class, 'clan', 'clanId');
    }


    public function getBestAttribute(): string
    {
        $players = Player::sum('skill')->where('clan', $this->clanId)->get();
        return $players['skill'];
    }
}
