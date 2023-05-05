<?php

namespace App\Http\Livewire\Map;

use App\Services\MapService;
use Livewire\Component;
use Livewire\WithPagination;

class TableMaps extends Component
{
    use WithPagination;

    public $search;
    public $server;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.map.table-maps', [
            'maps' => MapService::fromServer($this->server)->list($this->search)
        ]);
    }
}
