<?php

namespace App\Http\Controllers;

use App\Services\ServerService;
use App\Services\WeaponService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WeaponController extends Controller
{

    public function index(String $game)
    {
        $server = ServerService::fromGame($game);

        return view('pages.weapons.index', compact('server'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $game, string $code)
    {
        $weapon = WeaponService::findBy(['code' => $code, 'game' => $game])->with('weaponHits');
        $server = $weapon::server();

        return view('pages.weapons.show', compact('server', 'weapon'));
    }

}
