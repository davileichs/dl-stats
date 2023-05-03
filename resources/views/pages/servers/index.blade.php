@extends('template')

@section('content')
<x-container>
    <x-slot:title>
        Servers
    </x-slot>
    <x-slot:body>
        <livewire:server.table-servers :servers="$servers" />
    </x-slot>
</x-container>
@endsection
