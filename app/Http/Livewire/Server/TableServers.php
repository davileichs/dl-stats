<?php

namespace App\Http\Livewire\Server;

use App\Services\ServerService;
use Livewire\Component;

class TableServers extends Component
{

    public $servers;
    public $search = '';


    public function render()
    {
        return view('livewire.server.table-servers');
    }


}
