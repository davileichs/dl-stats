<?php

namespace App\Http\Livewire\Server;

use App\Models\Player;
use Livewire\Component;

class TablePlayers extends Component
{

    public $players;
    public $server;


    public function mount()
    {

        if (\DB::connection(config('database.active_stats'))->getSchemaBuilder()->getColumnListing('Livestats')) {
            $this->players = $this->server->livestats()->get();
        }


    }

    public function render()
    {
        return view('livewire.server.table-players');

    }
}
