<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerAction extends Model
{
    use HasFactory;


    protected $table = "Events_PlayerActions";


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }

    public function players()
    {
        $this->hasMany(Player::class, 'playerId', 'playerId');
    }
}
