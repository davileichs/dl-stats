@extends('template')

@section('content')
@include('parts.server-nav')
<x-container>
    <x-slot:title>
        {{ $server->name }}
    </x-slot>
    <x-slot:head>

    </x-slot>
    <x-slot:body>
        <div class="container text-center -bs-info-border-subtle mt-5 ">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link link-light active" id="nav-status-tab" data-bs-toggle="tab" data-bs-target="#nav-status" type="button" role="tab" aria-controls="nav-status" aria-selected="true">Status</button>
                    <button class="nav-link link-light" id="nav-statistics-tab" data-bs-toggle="tab" data-bs-target="#nav-statistics" type="button" role="tab" aria-controls="nav-statistics" aria-selected="false">Statistics</button>
                </div>
            </nav>
            <div class="tab-content pt-4" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-status" role="tabpanel" aria-labelledby="nav-status-tab">
                    <livewire:server.info :server="$server"  />

                </div>
                <div class="tab-pane fade" id="nav-statistics" role="tabpanel" aria-labelledby="nav-statistics-tab">

                    @include('pages.servers.top-tab')
                </div>
            </div>


          </div>

    </x-slot>
</x-container>
@endsection
