@extends('template')

@section('content')
@include('parts.server-nav')
<x-container>
    <x-slot:title>
        {{ $server->get()->name }}
    </x-slot>
    <x-slot:head>

    </x-slot>
    <x-slot:body>
        <div class="container text-center -bs-info-border-subtle mt-5 pb-4 ">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link link-light active" id="nav-status-tab" data-bs-toggle="tab" data-bs-target="#nav-status" type="button" role="tab" aria-controls="nav-status" aria-selected="true">Status</button>
                    <button class="nav-link link-light" id="nav-topplayers-tab" data-bs-toggle="tab" data-bs-target="#nav-topplayers" type="button" role="tab" aria-controls="nav-topplayers" aria-selected="false">Top Players</button>
                    <button class="nav-link link-light " id="nav-statistics-tab" data-bs-toggle="tab" data-bs-target="#nav-statistics" type="button" role="tab" aria-controls="nav-statistics" aria-selected="false">Statistics</button>
                    <button class="nav-link link-light " id="nav-session-tab" data-bs-toggle="tab" data-bs-target="#nav-session" type="button" role="tab" aria-controls="nav-session" aria-selected="false">Sessions</button>
                </div>
            </nav>
            <div class="tab-content pt-4">
                <div class="tab-pane fade show active" id="nav-status" role="tabpanel" aria-labelledby="nav-status-tab">
                    @include('pages.servers.stats-players-tab')
                </div>
                <div class="tab-pane fade" id="nav-topplayers" role="tabpanel" aria-labelledby="nav-topplayers-tab">
                    Desactived for a while because it demands too much memory for the poor server
                    <!--@include('pages.servers.top-players-tab') -->
                </div>
                <div class="tab-pane fade" id="nav-statistics" role="tabpanel" aria-labelledby="nav-statistics-tab">
                    @include('pages.servers.statistics-server-tab')
                </div>
                <div class="tab-pane fade" id="nav-session" role="tabpanel" aria-labelledby="nav-session-tab">
                    @include('pages.servers.sessions-map-tab')
                </div>
            </div>
          </div>

    </x-slot>
</x-container>
@endsection
