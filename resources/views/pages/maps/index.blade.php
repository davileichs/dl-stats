@extends('template')

@section('content')
@include('parts.server-nav')
<x-container>
    <x-slot:title>
        Maps
    </x-slot>
    <x-slot:body>
    <livewire:map.table-maps wire:key='rowId' :server="$server->get()" />
    </x-slot>
</x-container>
@endsection
