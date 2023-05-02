<?php

namespace App\Http\Livewire\Server;

use App\Models\Livestats;
use App\Models\Player;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TablePlayers extends Component
{

    public $server;

    public function render()
    {
        return view('livewire.server.table-players', [
            'teams'   =>  $this->server->getLivestats(),
        ]);

    }
}
