<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventsEntry extends Model
{
    use HasFactory;

    protected $table = "Events_Entries";



    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }
}
