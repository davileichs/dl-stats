<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use App\Services\ServerService;
use App\Services\PlayerService;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $game)
    {
        $server = ServerService::fromGame($game);

        return view('pages.players.index', compact('server'));

    }

    /**
     * Display the specified resource.
     */
    public function show(int $playerId)
    {
        $player = PlayerService::find($playerId);
        $server = $player::server();

        return view('pages.players.show', compact('player', 'server'));
    }


}
