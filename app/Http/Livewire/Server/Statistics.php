<?php

namespace App\Http\Livewire\Server;

use App\Services\ServerService;
use Livewire\Component;


class Statistics extends Component
{

    public $server;


    public function render()
    {
        return view('livewire.server.statistics', [
            'statisticsWeek'    => ServerService::find($this->server)->statisticsWeek(),
            'statisticsMonth'   => ServerService::find($this->server)->statisticsMonth(),
            'statisticsYear'    => ServerService::find($this->server)->statisticsYear(),
        ]);
    }
}
