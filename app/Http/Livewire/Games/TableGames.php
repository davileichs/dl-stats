<?php

namespace App\Http\Livewire\Games;

use Livewire\Component;

class TableGames extends Component
{

    public $games;

    public function render()
    {
        return view('livewire.games.table-games');
    }
}
