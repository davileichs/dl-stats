<?php

namespace App\Http\Livewire\Server;

use App\Services\ServerService;
use Livewire\Component;
use Carbon\Carbon;

class StatisticsDay extends Component
{

    public $server;
    public $day;
    public $today;
    protected $listeners = ['selectDay'];

    public function mount()
    {
        $this->today = Carbon::now()->format('d-m-Y');
    }

    public function selectDay($date)
    {
        $this->day = $date;
    }

    public function render()
    {

        return view('livewire.server.statistics-day',[
            'statisticsDay'     => ServerService::find($this->server)->statisticsDay($this->day),
        ]);
    }
}
