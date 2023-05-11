<?php

namespace App\Services;



use App\Models\PlayerWeaponHits;
use App\Models\Weapon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class WeaponService {


    protected static Weapon $weapon;
    protected static string $game;


    protected static function modelSimple(): Builder
    {
        return Weapon::select(['weaponId', 'code', 'name', 'game', 'modifier']);
    }

    protected static function model(): Builder
    {
        return Weapon::select('*');
    }

    public static function with($class): self
    {
        $with = self::{$class}()->toArray();
        foreach($with as $key => $value) {
            self::$weapon->$key = $value;
        }
        return new self();
    }


    public static function get(?string $field = null): String|Weapon
    {
        abort_if(!isset(self::$weapon), 404, 'No Weapon found');
        abort_if(!self::$weapon->exists, 404, 'No Weapon found');

        if($field) {
            return self::$weapon->{$field};
        }

        return self::$weapon;
    }

    public static function list(?string $search = null): Collection
    {
        $weapons = self::modelSimple();

        if ($search) {
            $weapons = $weapons->search('name', $search);
        }
        if(!empty(self::$game)) {
            $weapons = $weapons->where('game', self::$game)
                ->orderByDesc('name')
                ->get();
        }

        foreach($weapons as $weapon) {
            $weapon = self::find($weapon)->with('weaponHits');
        }

        return $weapons->sortByDesc('shots');
    }

    public static function find(int|Weapon $weapon): Self
    {
        if($weapon instanceof Weapon) {
            self::$weapon = $weapon;
        } else {
            self::$weapon = self::model()->where('weaponId', $weapon)->first();
        }
        abort_if(!self::$weapon->exists, 404, 'No Weapon found');

        return new self();
    }

    public static function findBy(array $fields): Self
    {
        self::$weapon = self::model()->with('weaponsHits')->where($fields)->first();

        //abort_if(!self::$weapon->exists, 404, 'No Weapon found');

        return new self();
    }

    public static function fromGame(string $game): self
    {
        self::$game = $game;
        return new self();
    }

    public static function server()
    {
        return ServerService::fromGame(self::$weapon->game);
    }

    public static function weaponHits(): ?PlayerWeaponHits
    {
        $weapon = self::get();
        return $weapon->weaponsHits()
            ->select([
                'weapon',
                \DB::raw('SUM(hits) as hits'),
                \DB::raw('SUM(damage) as damage'),
                \DB::raw('SUM(shots) as shots'),
                \DB::raw('round(SUM(hits) / SUM(shots)*100) as accuracy'),
            ])
            ->groupBy(['weapon'])
            ->first();
    }

    public static function weaponShots(): array
    {
        $weapon = self::$weapon;

        $shots = $weapon->weaponShots()
            ->select([
                'weapon',
                \DB::raw('SUM(head) as head'),
                \DB::raw('SUM(chest) as chest'),
                \DB::raw('SUM(stomach) as stomach'),
                \DB::raw('SUM(leftarm) as leftarm'),
                \DB::raw('SUM(rightarm) as rightarm'),
                \DB::raw('SUM(leftleg) as leftleg'),
                \DB::raw('SUM(rightleg) as rightleg'),
            ])
            ->groupBy(['weapon'])
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
        $weapon = self::$weapon;

        return $weapon->weaponsHits()
            ->select([
                'Players.lastName as nickname',
                'Players.playerId',
                'Players.skill',
                \DB::raw('SUM(hlstats_Events_Statsme.hits) as hits'),
                \DB::raw('SUM(hlstats_Events_Statsme.damage) as damage'),
                \DB::raw('SUM(hlstats_Events_Statsme.shots) as shots'),
                \DB::raw('round(SUM(hlstats_Events_Statsme.hits) / SUM(hlstats_Events_Statsme.shots)*100) as accuracy'),
                'weapon',
            ])
            ->join('Players', 'Players.playerId', 'Events_Statsme.playerId')
            ->where('Players.hideRanking', '0')
            //->where('Players.skill', '>', '1000')
            ->groupBy(['weapon', 'Players.playerId'])
            ->orderBy('damage', 'desc')
            ->limit($top)
            ->get();
    }

    public static function topMaps(?int $top = 10): Collection
    {
        $weapon = self::$weapon;

        return $weapon->weaponsHits()
            ->select([
                'map',
                'weapon',
                \DB::raw('SUM(hits) as hits'),
                \DB::raw('SUM(damage) as damage'),
                \DB::raw('SUM(shots) as shots'),
                \DB::raw('round(SUM(hits) / SUM(shots)*100) as accuracy'),

            ])
            ->groupBy(['weapon', 'map'])
            ->orderBy('shots', 'desc')
            ->limit($top)
            ->get();
    }

    public static function mapUsage(?string $date = null): array
    {
        return MapService::mapUsage(self::$weapon);
    }


}
