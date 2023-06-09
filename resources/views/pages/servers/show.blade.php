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
                    <livewire:server.info :server="$server->get()" />
                    <livewire:server.table-players :server="$server->get()" />
                </div>
                <div class="tab-pane fade" id="nav-topplayers" role="tabpanel" aria-labelledby="nav-topplayers-tab">
                    <livewire:server.top-players :server="$server->get()" />
                </div>
                <div class="tab-pane fade" id="nav-statistics" role="tabpanel" aria-labelledby="nav-statistics-tab">
                    <livewire:server.statistics :server="$server->get()" />
                </div>
                <div class="tab-pane fade" id="nav-session" role="tabpanel" aria-labelledby="nav-session-tab">
                    <livewire:server.session :server="$server->get()" />
                </div>
            </div>
          </div>

    </x-slot>
</x-container>
@endsection
@section('styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
@endsection
