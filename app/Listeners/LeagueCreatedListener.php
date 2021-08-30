<?php

namespace App\Listeners;

use App\Events\LeagueCreatedEvent;
use App\Services\MatchService;

class LeagueCreatedListener
{
    private MatchService $matchService;

    public function __construct(MatchService $matchService)
    {
        $this->matchService = $matchService;
    }

    public function handle(LeagueCreatedEvent $leagueCreatedEvent): void
    {
        $this->matchService->generateSchedule($leagueCreatedEvent->league->teams);
    }
}
