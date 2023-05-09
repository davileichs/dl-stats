<?php

namespace App\Http\Livewire\Player;

use App\Services\PlayerService;
use Livewire\Component;

class TablePoints extends Component
{

    public $playerId;
    public $readyToLoad = false;



    public function loadSession()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.player.table-points', [
            'rewards'       => $this->readyToLoad ? PlayerService::find($this->playerId)->rewards() : [],
            'listActions'   => $this->readyToLoad ? PlayerService::find($this->playerId)->listActions() : []
        ]);
    }
}
