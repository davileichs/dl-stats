<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventsSuicide extends Model
{
    use HasFactory;

    protected $table = "Events_Suicides";


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class, 'playerId', 'playerId');
    }

    public function servers(): HasMany
    {
        return $this->hasMany(Server::class, 'serverId', 'serverId');
    }
}
