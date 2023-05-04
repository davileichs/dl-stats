<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServerLoad extends Model
{
    use HasFactory;


    protected $table = "server_load";

    protected $primaryKey = 'server_id';


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }

    public function servers(): HasMany
    {
        return $this->hasMany(ServerLoad::class, 'playerId', 'player_id');
    }
}
