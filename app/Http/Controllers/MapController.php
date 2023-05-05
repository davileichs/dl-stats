<?php

namespace App\Http\Controllers;

use App\Services\MapService;
use App\Services\ServerService;
use Illuminate\Http\Request;

class MapController extends Controller
{


    public function index(String $game)
    {
        $server = ServerService::fromGame($game);

        return view('pages.maps.index', compact('server'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $game, string $map)
    {
        $map = MapService::findBy(['map' => $map, 'game' => $game]);
        $server = $map::server();
        $map->playTime();
        return view('pages.maps.show', compact('server', 'map'));
    }
}
