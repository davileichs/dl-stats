<?php

namespace App\Http\Livewire\Server;

use App\Models\Player;
use Livewire\Component;

class TablePlayers extends Component
{

    public $players;

    public function render()
    {
        return view('livewire.server.table-players');

    }
}
