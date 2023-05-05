<div>
    <div class="row">

        <div class="col-md-4">
            <x-top-block :topPlayers="$topTriggerPlayers">
                <x-slot:title>
                    TOP Trigger
                </x-slot>
            </x-top-block>
        </div>

        <div class="col-md-4">
            <x-top-block :topPlayers="$topDefenderPlayers">
                <x-slot:title>
                    TOP Defender
                </x-slot>
            </x-top-block>
        </div>

        <div class="col-md-4">
            <x-top-block :topPlayers="$topWinnerExtremePlayers">
                <x-slot:title>
                    TOP Winner in Extreme
                </x-slot>
            </x-top-block>
        </div>

        <div class="col-md-4">
            <x-top-block :topPlayers="$topBossDamagePlayers">
                <x-slot:title>
                    TOP Boss Damage
                </x-slot>
            </x-top-block>
        </div>

        <div class="col-md-4">
            <x-top-block :topPlayers="$topSoloPlayers">
                <x-slot:title>
                    TOP Solo
                </x-slot>
            </x-top-block>
        </div>

        <div class="col-md-4">
            <x-top-block :topPlayers="$topZombieDamagePlayers">
                <x-slot:title>
                    TOP Zombie Damage
                </x-slot>
            </x-top-block>
        </div>

        <div class="col-md-4">
            <x-top-block :topPlayers="$topMotherZombiePlayers" color="danger">
                <x-slot:title>
                    TOP Mother Zombie
                </x-slot>
            </x-top-block>
        </div>

        <div class="col-md-4">
            <x-top-block :topPlayers="$topInfectorPlayers" color="danger">
                <x-slot:title>
                    TOP Infector
                </x-slot>
            </x-top-block>
        </div>

    </div>

</div>
