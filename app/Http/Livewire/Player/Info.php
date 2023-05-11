<?php

namespace App\Http\Livewire\Player;

use Livewire\Component;

class Info extends Component
{

    public $player;
    public $also_name;
    public $racetime;


    public function render()
    {
        return view('livewire.player.info');
    }
}
