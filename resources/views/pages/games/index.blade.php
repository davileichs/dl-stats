@extends('template')

@section('content')
<x-container>
    <x-slot:title>
        Games
    </x-slot>
    <x-slot:body>
        <livewire:games.table-games  :games="$games"/>
    </x-slot>
</x-container>
@endsection
