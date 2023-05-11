<?php

namespace App\Http\Livewire\Map;

use App\Services\MapService;
use Livewire\Component;

class TableRacetime extends Component
{

    public $mapId;
    public $readyToLoad = false;

    public function loadSession()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.map.table-racetime', [
            'playersRaceTime'    => $this->readyToLoad ? MapService::find($this->mapId)->playersRaceTime() : [],
        ]);
    }
}
