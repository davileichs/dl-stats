<?php

namespace App\Http\Controllers;


use App\Models\Game;
use App\Models\Server;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(?Server $server)
    {
        if($server->exists) {
            $games = $server->games()->get();
        } else {
            $games = Game::all();
        }

        return view('pages.games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code)
    {
        $game = Game::where('code', $code)->orderby('name', 'ASC')->active()->first();
        return view(
            'pages.games.show',
            compact(
                'game',
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
