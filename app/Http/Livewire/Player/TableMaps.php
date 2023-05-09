<?php

namespace App\Http\Livewire\Player;

use Livewire\Component;
use App\Services\PlayerService;
use Carbon\Carbon;

class TableMaps extends Component
{

    public $playerId;
    public $date;
    public $maps;
    protected $listeners = ['selectSession'];
    public $readyToLoad = false;


    public function mount()
    {
        $this->date = Carbon::now()->format('d-m-Y');
    }

    public function selectSession($date)
    {
        $this->date = $date;
    }

    public function loadSession()
    {
        $this->readyToLoad = true;
    }


    public function render()
    {
        $this->maps = $this->readyToLoad ? PlayerService::find($this->playerId)->mapsPlayed(Carbon::parse($this->date)->toDateString()) : [];
        return view('livewire.player.table-maps');
    }
}
