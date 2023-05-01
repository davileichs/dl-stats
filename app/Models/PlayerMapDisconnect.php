<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerMapDisconnect extends Model
{
    use HasFactory;


    protected $table = "Events_Disconnects";


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }
}
