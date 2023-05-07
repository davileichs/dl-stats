<?php

namespace App\Http\Livewire\Server;

use App\Services\ServerService;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Session extends Component
{

    public $server;
    public $date;
    public $mapUsage;
    protected $listeners = ['selectDay'];
    public $readyToLoad = false;

    public function mount()
    {
        $this->date = Carbon::now()->format('d-m-Y');
    }

    public function selectDay($date)
    {
        $this->date = $date;

    }

    public function loadSession()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        $this->mapUsage = $this->readyToLoad ? ServerService::find($this->server)->playersOnMap(Carbon::parse($this->date)->toDateString()) : [];
        return view('livewire.server.session');
    }
}
