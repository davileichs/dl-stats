<?php

namespace App\Http\Livewire\Weapon;

use App\Services\WeaponService;
use Livewire\Component;

class TableWeapons extends Component
{

    public $search;
    public $server;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.weapon.table-weapons', [
            'weapons' => WeaponService::fromGame($this->server->game)->list($this->search)
        ]);
    }
}
