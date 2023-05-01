<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerName extends Model
{
    use HasFactory;

    protected $table = "PlayerNames";


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }
}
