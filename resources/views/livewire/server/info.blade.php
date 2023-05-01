<div class="row"  wire:poll>
    <div class="col-md-3">
        <x-block-clip>
           <x-slot:value>
            {{ $server->act_map }}
           </x-slot>
           <x-slot:title>
            Current Map
           </x-slot>
        </x-block-clip>
      </div>

      <div class="col-md-3">
        <x-block-clip>
            <x-slot:value>
                {{ $server->act_players }}/{{ $server->max_players }}
            </x-slot>
            <x-slot:title>
                Players
            </x-slot>
         </x-block-clip>
      </div>

      <div class="col-md-3">
        <x-block-clip>
            <x-slot:value>
                {{ $server->map_ct_wins }}:{{ $server->map_ts_wins }}
            </x-slot>
            <x-slot:title>
                Round
            </x-slot>
         </x-block-clip>
      </div>

      <div class="col-md-3">
        <x-block-clip>
            <x-slot:value>
                {{ $server->played }}
            </x-slot>
            <x-slot:title>
                Time playing
            </x-slot>
         </x-block-clip>
      </div>
</div>
