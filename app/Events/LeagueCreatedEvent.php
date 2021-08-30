<?php

namespace App\Events;

use App\Models\League;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeagueCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public League $league;

    public function __construct(League $league)
    {
        $this->league = $league;
    }
}
