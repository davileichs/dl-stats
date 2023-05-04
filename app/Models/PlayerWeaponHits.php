<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlayerWeaponHits extends Model
{
    use HasFactory;

    protected $table = "Events_Statsme";


    public function __construct()
    {
        parent::__construct();
        $this->connection = config('database.active_stats');
    }

    public function players(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'playerId', 'playerId');
    }

    public function getAccuracyAttribute()
    {
        return percent($this->hits, $this->shots);
    }
}
