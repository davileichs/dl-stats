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
        $maps = EventsEntry::select([
            'map',
            \DB::raw('(
                SELECT
                count(id)
                FROM hlstats_Events_Entries hee
                where hee.map = hlstats_Events_Entries.map
                and time(hee.eventTime) > "12:00:00"
                group by map)
            as popularity')])
            ->where('serverId', self::$server->serverId)
            ->whereDate('eventTime', '>', Carbon::now()->subMonth()->toDateTime());

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

    public function playersRaceTime()
    {
        $map = strtolower(self::$map->map);
        $times = new Collection();
        $page = 0;
        do {
            $request = \Http::get('https://racebackend.unloze.com/racetimer_endpoints-1.0/api/timers/map/'.$map.'/1/'.$page);

            $response = $request->json();
            $times = $times->merge($response);

            if($request->status() != '200') {
                return [];
            }

            $page++;
        } while(count($response) >= 20 && $page < 8);


        return $times->unique()->sortby('position')->take(100);
    }

    public static function topShootPlayers(int $top = 10): Collection
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
            ->orderBy('damage', 'desc')
            ->limit($top)
            ->get();
    }

    public static function topPlayers(int $top = 10): Collection
    {
        $rows = self::$map->playerActions()
        ->select([
            'Players.playerId',
            'Players.lastName as nickname',
            \DB::raw('SUM(reward_player) as points')
        ])
        ->join('Actions', 'Events_PlayerActions.actionId', 'Actions.id')
        ->join('Players', 'Players.playerId', 'Events_PlayerActions.playerId')
        ->groupBy('Players.playerId')
        ->where('Players.hideranking', 0)
        ->where('Players.game', self::get()->game)
        ->where('eventTime', '>=', Carbon::now()->subMonth())
        ->groupBy(['Players.playerId'])
        ->orderBy('points', 'desc')
        ->limit($top)
        ->get();

        return $rows;
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

    public static function playersOnMap(mixed $rows, bool $continuous = true): array
    {
        $list = array();
        foreach($rows as $k=>$row) {

            if($k == 0 || strcmp($rows[($k-1)]->map, $row->map) !== 0) {
                if($k==0) {
                    $newKey = substr($row->eventTime, -8, 5);
                    $date_start = $row->eventTime;
                } else {
                    if($continuous) {
                        $newKey = substr($rows[($k-1)]->eventTime, -8, 5);
                    } else {
                        $newKey = substr($row->eventTime, -8, 5);
                    }
                    $date_start = $row->eventTime;
                }
                $list[$newKey] = ['map' => $row->map, 'players' => array(), 'end_at' => substr($row->eventTime, -8, 5), 'date_start' => $date_start, 'date_end' => $row->eventTime];
            } else {
                $list[$newKey]['end_at'] = substr($row->eventTime, -8, 5);
                $list[$newKey]['date_end'] = $row->eventTime;
            }

            $wins[$newKey][$row->playerId][] = self::checkWinningMap($row->code);
            if(empty($list[$newKey]['players'][$row->playerId])) {
                $list[$newKey]['players'][$row->playerId] = (object)([
                    'playerId'  => $row->playerId,
                    'nickname'  => $row->nickname ?? '',
                    'points'    => $row->bonus
                ]);
            } else {
                $list[$newKey]['players'][$row->playerId]->points += $row->bonus;
            }

        }
        $list = array_reverse($list);
        foreach($list as $k => &$value) {
            foreach($value['players'] as $id=>$player) {
                $player->mapwin = implode(' | ', array_filter( array_unique($wins[$k][$id]) ) );
            }
            $value['players'] =  Collection::make($value['players'])->sortByDesc('points');
            $startTime = Carbon::parse($value['date_start']);
            $finishTime = Carbon::parse($value['date_end']);
            $diff = $finishTime->diffInSeconds($startTime);
            if ($diff == 0) {
                $value['total_time'] = 'AFK';
            } else {
                $value['total_time'] = time2string($diff, ['h', 'm']);
            }

        }

       return $list;
    }

    protected static function checkWinningMap($code) {

        switch($code) {
            case 'ze_h_win_0':
            case 'ze_h_win_0_hh':
            case 'event_win_1':
                return 'EASY';
            case 'ze_h_win_1':
            case 'ze_h_win_1_hh':
            case 'event_win_2':
                return 'NORMAL';
            case 'ze_h_win_2':
            case 'ze_h_win_2_hh':
            case 'event_win_3':
                return 'HARD';
            case 'ze_h_win_3':
            case 'ze_h_win_3_hh':
            case 'event_win_4':
                return 'EXTREME';
            default:
            return null;
        }

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
