<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Standing extends Model
{
    use HasFactory;

    protected $fillable = [
        'week', 'league_id', 'team_id', 'goal_for', 'goal_against', 'goal_difference', 'win', 'lost', 'draw',
        'points', 'position',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
