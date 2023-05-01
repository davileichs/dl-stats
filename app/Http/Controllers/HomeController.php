<?php

namespace App\Http\Controllers;

use App\Models\Clan;
use App\Models\Game;
use App\Models\Server;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::orderby('name', 'ASC')->active()->get();

        $totalPlayers = Server::sum('players');
        $totalKills = Server::sum('kills');
        $totalClans = Clan::count();
        $totalServers = Server::active()->count();
        $totalGames = Game::active()->count();

        return view(
            'pages.home',
            compact(
                'games',
                'totalPlayers',
                'totalKills',
                'totalClans',
                'totalServers',
                'totalGames'
            )
        );
    }

}
