<?php

namespace App\Http\Livewire\Player;

use App\Models\Player;
use Livewire\Component;
use Livewire\WithPagination;

class TablePlayers extends Component
{
    use WithPagination;

    public $search;
    public $game;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';


    public function mount()
    {
        $this->game = \Route::current()->parameter('game');
    }

    public function render()
    {
        return view('livewire.player.table-players', [
            'players'   => Player::select(['lastName', 'skill', 'activity', 'playerId', 'connection_time', 'game'])
                            ->where('game', $this->game)
                            ->search('lastName', $this->search)
                            ->orderByDesc('skill')
                            ->with('games')
                            ->paginate(50),
        ]);
    }

    public function paginationView()
    {
        return 'pagination::bootstrap-5';
    }
}
