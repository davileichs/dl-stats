<div class="row">

    <div class="col-md-4">
        <x-top-block :topPlayers="$server->topTriggerPlayers()">
            <x-slot:title>
                TOP Trigger
            </x-slot>
        </x-top-block>
    </div>

    <div class="col-md-4">
        <x-top-block :topPlayers="$server->topDefenderPlayers()">
            <x-slot:title>
                TOP Defender
            </x-slot>
        </x-top-block>
    </div>

    <div class="col-md-4">
        <x-top-block :topPlayers="$server->topWinnerExtremePlayers()">
            <x-slot:title>
                TOP Winner in Extreme
            </x-slot>
        </x-top-block>
    </div>

    <div class="col-md-4">
        <x-top-block :topPlayers="$server->topBossDamagePlayers()">
            <x-slot:title>
                TOP Boss Damage
            </x-slot>
        </x-top-block>
    </div>

    <div class="col-md-4">
        <x-top-block :topPlayers="$server->topSoloPlayers()">
            <x-slot:title>
                TOP Solo
            </x-slot>
        </x-top-block>
    </div>

    <div class="col-md-4">
        <x-top-block :topPlayers="$server->topTriggerPlayers()">
            <x-slot:title>
                TOP Defender
            </x-slot>
        </x-top-block>
    </div>

    <div class="col-md-4">
        <x-top-block :topPlayers="$server->topZombieDamagePlayers()">
            <x-slot:title>
                TOP Zombie Damage
            </x-slot>
        </x-top-block>
    </div>

    <div class="col-md-4">
        <x-top-block :topPlayers="$server->topMotherZombiePlayers()" color="danger">
            <x-slot:title>
                TOP Mother Zombie
            </x-slot>
        </x-top-block>
    </div>

    <div class="col-md-4">
        <x-top-block :topPlayers="$server->topInfectorPlayers()" color="danger">
            <x-slot:title>
                TOP Infector
            </x-slot>
        </x-top-block>
    </div>

</div>
