@extends('template')

@section('content')
@include('parts.server-nav')
<x-container>
    <x-slot:title>
        Players
    </x-slot>
    <x-slot:body>
    <livewire:player.table-players wire:key='playerId' />
    </x-slot>
</x-container>
@endsection
