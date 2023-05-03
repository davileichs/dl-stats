<?php

namespace App\Http\Livewire\Player;

use App\Models\Player;
use App\Services\PlayerService;
use Livewire\Component;
use Livewire\WithPagination;

class TablePlayers extends Component
{
    use WithPagination;

    public $search;

    public $server;

    protected $paginationTheme = 'bootstrap';


    public function render()
    {
        return view('livewire.player.table-players', [
            'players' => PlayerService::fromGame($this->server->game)->list($this->search)
        ]);
    }


}
