<?php

namespace App\Http\Livewire\Server;

use App\Models\Server;
use Livewire\Component;

class TableServers extends Component
{

    public $search = '';


    public function render()
    {
        return view('livewire.server.table-servers', [
            'servers'   => Server::select(['serverId', 'name', 'act_players', 'max_players', 'publicaddress', 'act_map', 'map_started'])->search('name',$this->search)->get(),
        ]);
    }


}
