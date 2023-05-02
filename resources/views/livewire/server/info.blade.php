<div class="row" wire:poll.1s>
    <div class="col-md-5">
        <x-block-clip>
           <x-slot:value>
            {{ $server->act_map }}
           </x-slot>
           <x-slot:title>
            Current Map
           </x-slot>
        </x-block-clip>
      </div>

      <div class="col-md-2">
        <x-block-clip>
            <x-slot:value>
                {{ $server->act_players -1 }}/{{ $server->max_players -1  }}
            </x-slot>
            <x-slot:title>
                Players
            </x-slot>
         </x-block-clip>
      </div>

      <div class="col-md-2">
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
