<?php

namespace App\Http\Livewire\Server;

use App\Services\ServerService;
use Livewire\Component;

class TablePlayers extends Component
{

    public $server;

    public function render()
    {
        return view('livewire.server.table-players', [
            'teams'   => ServerService::set($this->server)->livestatsByTeam(),
        ]);

    }
}
