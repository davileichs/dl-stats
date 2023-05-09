<?php

namespace App\Http\Livewire\Player;

use Livewire\Component;
use App\Services\PlayerService;

class TableWeapons extends Component
{

    public $playerId;
    public $readyToLoad = false;
    public $player;
    public $weapons;


    public function loadSession()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        $this->player = PlayerService::find($this->playerId)->get();
        $this->weapons = $this->readyToLoad ? PlayerService::find($this->playerId)->listWeaponsHits() : [];

        return view('livewire.player.table-weapons');
    }
}
