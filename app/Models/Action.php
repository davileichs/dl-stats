<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasManyThrough;

class Action extends Model
{
    use HasFactory;


    protected $table = "Actions";


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }


    public function playerActions()
    {
        $this->hasMany(PlayerAction::class, 'playerId', 'id');
    }



}

