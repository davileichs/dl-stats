<?php

namespace App\Services;



use App\Models\PlayerMapHits;
use App\Models\Map;
use App\Models\EventsEntry;
use App\Models\Server;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MapService {


    protected static Map $map;
    protected static Server $server;


    protected static function modelSimple(): Builder
    {
        return Map::select(['game', 'map','rowId']);
    }

    protected static function model(): Builder
    {
        return Map::select('*');
    }

    public static function with($class): self
    {
        $with = self::{$class}()->toArray();
        foreach($with as $key => $value) {
            self::$map->$key = $value;
        }
        return new self();
    }


    public static function get(?string $field = null): String|Map
    {
        abort_if(!isset(self::$map), 404, 'No Map found');
        abort_if(!self::$map->exists, 404, 'No Map found');

        if($field) {
            return self::$map->{$field};
        }

        return self::$map;
    }

    public static function list(?string $search = null)
    {
        $maps = EventsEntry::select(['map', \DB::raw('count(id) as popularity')])
        ->where('serverId', self::$server->serverId)->whereDate('eventTime', '>', Carbon::now()->subMonth()->toDateTime());

        if ($search) {
            $maps = $maps->search('map', $search);
        }

        $maps = $maps->groupBy('map')->orderBy('popularity', 'desc')->paginate(50);

        return $maps;
    }

    public static function find(int|Map $map): Self
    {
        if($map instanceof Map) {
            self::$map = $map;
        } else {
            self::$map = self::model()->where('rowId', $map)->first();
        }
        abort_if(!self::$map->exists, 404, 'No Map found');

        return new self();
    }

    public static function findBy(array $fields): Self
    {
        self::$map = self::model()->where($fields)->with('servers')->first();
        self::fromServer(self::$map->servers()->first());

        //abort_if(!self::$map->exists, 404, 'No Map found');

        return new self();
    }

    public static function fromServer(string|Server $server): self
    {
        if($server instanceof Server) {
            self::$server = $server;
        } else {
            self::$server = ServerService::find($server)->get();
        }

        return new self();
    }

    public static function server()
    {
        $map = self::get();
        return ServerService::find($map->servers()->first());
    }

    public static function MapHits(): ?PlayerMapHits
    {
        $map = self::get();
        return $map->MapsHits()
            ->select([
                'Map',
                \DB::raw('SUM(hits) as hits'),
                \DB::raw('SUM(damage) as damage'),
                \DB::raw('SUM(shots) as shots'),
                \DB::raw('round(SUM(hits) / SUM(shots)*100) as accuracy'),
            ])
            ->groupBy(['Map'])
            ->first();
    }

    public static function MapShots(): array
    {
        $map = self::$map;

        $shots = $map->MapShots()
            ->select([
                'Map',
                \DB::raw('SUM(head) as head'),
                \DB::raw('SUM(chest) as chest'),
                \DB::raw('SUM(stomach) as stomach'),
                \DB::raw('SUM(leftarm) as leftarm'),
                \DB::raw('SUM(rightarm) as rightarm'),
                \DB::raw('SUM(leftleg) as leftleg'),
                \DB::raw('SUM(rightleg) as rightleg'),
            ])
            ->groupBy(['Map'])
            ->first();

            return array(
                'head'      => $shots['head'] ?? 0,
                'rightarm'  => $shots['rightarm'] ?? 0,
                'chest'     => $shots['chest'] ?? 0,
                'rightleg'  => $shots['rightleg'] ?? 0,
                'leftleg'   => $shots['leftleg'] ?? 0,
                'stomach'   => $shots['stomach'] ?? 0,
                'leftarm'   => $shots['leftarm'] ?? 0,
            );
    }

    public static function topPlayers(?int $top = 10): Collection
    {
        $map = self::$map;

        return $map->weaponsHits()
            ->select([
                'Players.lastName as nickname',
                'Players.playerId',
                'Players.skill',
                \DB::raw('SUM(hlstats_Events_Statsme.hits) as hits'),
                \DB::raw('SUM(hlstats_Events_Statsme.damage) as damage'),
                \DB::raw('SUM(hlstats_Events_Statsme.shots) as shots'),
                \DB::raw('round(SUM(hlstats_Events_Statsme.hits) / SUM(hlstats_Events_Statsme.shots)*100) as accuracy'),
                'Map',
            ])
            ->join('Players', 'Players.playerId', 'Events_Statsme.playerId')
            ->where('Events_Statsme.shots', '>', '500')
            ->where('Players.hideRanking', '0')
            ->where('Players.skill', '>', '1000')
            ->groupBy(['Map', 'Players.playerId'])
            ->orderBy('accuracy', 'desc')
            ->limit($top)
            ->get();
    }


    public static function mapUsage(mixed $model = null, ?string $date = null): array
    {
        if(!$model) {
            $model = self::$map;
        }
        if(!$date) {
            $date = Carbon::now()->toDateString();
        }

        $rows = $model->weaponsHits()
            ->select([
                'Players.lastName as nickname',
                'Players.playerId',
                'Events_Statsme.*'
            ])
            ->join('Players', 'Players.playerId', 'Events_Statsme.playerId')
            ->where('hideRanking', 0)
            ->whereDate('eventTime', $date)
            ->orderBy('Events_Statsme.eventTime', 'asc')
            ->get();

        $list = array();
        $count = 1;
        foreach($rows as $k=>$row) {

            if($k == 0 || strcmp($rows[($k-1)]->map, $row->map) !== 0) {
                if($k==0) {
                    $newKey = substr($row->eventTime, -8, 5);
                    $date_start = $row->eventTime;
                } else {
                    $newKey = substr($rows[($k-1)]->eventTime, -8, 5);
                    $date_start = $row->eventTime;
                }

                $list[$newKey] = ['map' => $row->map, 'players' => array(), 'end_at' => null, 'date_start' => $date_start, 'date_end' => null];
            } else {
                $list[$newKey]['end_at'] = substr($row->eventTime, -8, 5);
                $list[$newKey]['date_end'] = $row->eventTime;
            }

            if(empty($list[$newKey]['players'][$row->playerId])) {
                $list[$newKey]['players'][$row->playerId] = (object)([
                    'playerId'  => $row->playerId,
                    'nickname'  => $row->nickname ?? '',
                    'shots'     => $row->shots,
                    'hits'      => $row->hits,
                    'damage'    => $row->damage,
                ]);
            } else {
                $list[$newKey]['players'][$row->playerId]->shots += $row->shots;
                $list[$newKey]['players'][$row->playerId]->hits += $row->hits;
                $list[$newKey]['players'][$row->playerId]->damage += $row->damage;
            }


        }
        foreach($list as &$key) {
                $key['players'] =  Collection::make($key['players'])->sortByDesc('damage');
                $startTime = Carbon::parse($key['date_start']);
                $finishTime = Carbon::parse($key['date_end']);
                $key['total_time'] = time2string($finishTime->diffInSeconds($startTime), ['h', 'm']);
        }
       return (array_reverse($list));
    }

    public function playTime(): array
    {
        $initDate = Carbon::now()->subMonth();

        $entries = self::$map->entries()
            ->whereDate('eventTime', '>', $initDate->toDateTime())
            ->orderBy('eventTime', 'desc')->get();

        $dates = array();


        while($initDate->isPast()) {
            $key = substr($initDate->toDateTimeString(), 0, 13) ;
            $dates[$key] = 0;
            $initDate = $initDate->addHour();
        }

        foreach($entries as $entry) {
            $entry_date = substr($entry->eventTime, 0, 13);
            if(array_key_exists($entry_date, $dates)) {
                if(empty($dates[$entry_date])) {
                    $dates[$entry_date] = 1;
                } else {
                    $dates[$entry_date] += 1;
                }
            }
        }

        return ($dates);
    }


}
