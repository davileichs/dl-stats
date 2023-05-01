<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerSteam extends Model
{
    use HasFactory;

    protected $table = "PlayerUniqueIds";


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }


}
