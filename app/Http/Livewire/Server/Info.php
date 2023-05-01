<?php

namespace App\Http\Livewire\Server;

use Livewire\Component;

class Info extends Component
{

    public $server;
    public function render()
    {
        return view('livewire.server.info');
    }
}
