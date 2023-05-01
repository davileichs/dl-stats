<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerWeaponShot extends Model
{
    use HasFactory;

    protected $table = "Events_Statsme2";


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }
}
