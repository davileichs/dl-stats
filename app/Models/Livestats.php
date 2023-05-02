<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestats extends Model
{
    use HasFactory;

    protected $table = "Livestats";

    protected $primaryKey = 'playerId';


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }


    public function getTimeAttribute()
    {
        $ts1 = ($this->connected);
        $ts2 = time();
        $seconds_diff = $ts2 - $ts1;
        $minutes = ($seconds_diff/60);

        return time2string($seconds_diff, ['h', 'm', 's']);
    }
}
