<?php

namespace App\Http\Livewire\Server;

use App\Services\ServerService;
use Livewire\Component;

class TopPlayers extends Component
{

    public $server;
    public $readyToLoad = false;


    public function loadTopPlayers()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.server.top-players', [
            'topTriggerPlayers'         => $this->readyToLoad ? ServerService::find($this->server)->topTriggerPlayers() : [],
            'topDefenderPlayers'        => $this->readyToLoad ? ServerService::find($this->server)->topDefenderPlayers() : [],
            'topWinnerPlayers'          => $this->readyToLoad ? ServerService::find($this->server)->topWinnerPlayers() : [],
            'topBossDamagePlayers'      => $this->readyToLoad ? ServerService::find($this->server)->topBossDamagePlayers() : [],
            'topSoloPlayers'            => $this->readyToLoad ? ServerService::find($this->server)->topSoloPlayers() : [],
            'topZombieDamagePlayers'    => $this->readyToLoad ? ServerService::find($this->server)->topZombieDamagePlayers() : [],
            'topMotherZombiePlayers'    => $this->readyToLoad ? ServerService::find($this->server)->topMotherZombiePlayers() : [],
            'topInfectorPlayers'        => $this->readyToLoad ? ServerService::find($this->server)->topInfectorPlayers() : []
        ]);
    }
}
