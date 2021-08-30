<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class League extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'current_week'];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function currentStanding(): Collection
    {
        return $this->hasMany(Standing::class)->where('week', $this->current_week)->orderBy('position')->get();
    }

    public function currentWeekMatches(): Collection
    {
        return $this->hasMany(Match::class)->where('week', $this->current_week)->get();
    }
}
