<?php

namespace App\Http\Livewire\Player;

use Livewire\Component;
use App\Services\PlayerService;
use Carbon\Carbon;

class History extends Component
{

    public $server;
    public $game;



    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.player.history', [
            'history' => PlayerService::history($this->game)
        ]);
    }
}
