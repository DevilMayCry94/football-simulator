<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\MatchUpdateRequest;
use App\Models\Match;
use App\Services\MatchService;
use App\Services\StandingService;
use App\ValueObject\MatchEntity;

class MatchController extends Controller
{
    private MatchService $matchService;
    private StandingService $standingService;

    public function __construct(MatchService $matchService, StandingService $standingService)
    {
        $this->matchService = $matchService;
        $this->standingService = $standingService;
    }

    public function update(Match $match, MatchUpdateRequest $matchUpdateRequest)
    {
        if ($this->matchService->update($match->id, $matchUpdateRequest->validated())) {
            $league = $match->league;
            for ($i = $match->week; $i <= $league->current_week; $i++) {
                $matches = collect();
                foreach ($this->matchService->getMatchesByWeek($league, $i) as $match) {
                    $matches->push(new MatchEntity($match->homeTeam, $match->awayTeam, $i, $match->home_team_score, $match->away_team_score));
                }
                $standings = $this->standingService->calculateStanding($league, $matches, $i);
                $this->standingService->updateStandingPosition($standings);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
