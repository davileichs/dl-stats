<?php

namespace App\Http\Livewire\Player;

use App\Services\PlayerService;
use Livewire\Component;

class TableRacetime extends Component
{

    public $playerId;
    public $readyToLoad = false;

    public function loadSession()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.player.table-racetime',[
            'playerRaceTime'    => $this->readyToLoad ? PlayerService::find($this->playerId)->playerRaceTime() : [],
            'mapsRaceTime'       => $this->readyToLoad ? PlayerService::find($this->playerId)->mapsRaceTime() : []
        ]);
    }
}
