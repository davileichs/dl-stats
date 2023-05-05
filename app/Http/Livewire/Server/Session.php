<?php

namespace App\Http\Livewire\Server;

use App\Services\ServerService;
use Livewire\Component;

class Session extends Component
{

    public $server;

    public function render()
    {
        return view('livewire.server.session', [
            'mapUsage'   => ServerService::find($this->server)->mapUsage(),
        ]);
    }
}
