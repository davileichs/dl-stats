@extends('template')

@section('content')
@include('parts.server-nav')
<x-container>
    <x-slot:title>
        Weapons
    </x-slot>
    <x-slot:body>
    <livewire:weapon.table-weapons wire:key='weaponId' :server="$server->get()" />
    </x-slot>
</x-container>
@endsection
