<?php

namespace App\Http\Livewire\Server;

use App\Services\ServerService;
use Livewire\Component;

class TopPlayers extends Component
{

    public $server;

    public function render()
    {
        return view('livewire.server.top-players', [
            'topTriggerPlayers'   => ServerService::find($this->server)->topTriggerPlayers(),
            'topDefenderPlayers'   => ServerService::find($this->server)->topDefenderPlayers(),
            'topWinnerPlayers'   => ServerService::find($this->server)->topWinnerPlayers(),
            'topBossDamagePlayers'   => ServerService::find($this->server)->topBossDamagePlayers(),
            'topSoloPlayers'   => ServerService::find($this->server)->topSoloPlayers(),
            'topZombieDamagePlayers'   => ServerService::find($this->server)->topZombieDamagePlayers(),
            'topMotherZombiePlayers'   => ServerService::find($this->server)->topMotherZombiePlayers(),
            'topInfectorPlayers'   => ServerService::find($this->server)->topInfectorPlayers()
        ]);
    }
}
