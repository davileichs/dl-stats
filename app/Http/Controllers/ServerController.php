<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Services\ServerService;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $servers = ServerService::list();
        return view('pages.servers.index', compact('servers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Server $server)
    {
        $server = ServerService::find($server);
        return view('pages.servers.show', compact('server'));
    }

}
