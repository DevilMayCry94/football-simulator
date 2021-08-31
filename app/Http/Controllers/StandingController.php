<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\League;
use App\Services\LeagueService;
use App\Services\MatchService;
use App\Services\Prediction;
use App\Services\StandingService;
use Illuminate\Http\JsonResponse;

class StandingController extends Controller
{

    private LeagueService $leagueService;
    private StandingService $standingService;
    private MatchService $matchService;

    public function __construct(LeagueService $leagueService, StandingService $standingService, MatchService $matchService)
    {
        $this->leagueService = $leagueService;
        $this->standingService = $standingService;
        $this->matchService = $matchService;
    }

    public function getByWeek(League $league, int $weekNumber)
    {
        $countWeek = ($league->teams()->count() - 1) * 2;
        $weekNumber = $weekNumber > $countWeek ? $countWeek : $weekNumber;
        $weekNumber = $weekNumber > 0 ? $weekNumber : 0;

        if ($league->current_week < $weekNumber) {
            if ($league->current_week + 1 < $weekNumber) {
                throw new \Exception('Week number should be the next week from current week');
            }

            $this->leagueService->nextWeek($league, $weekNumber);
        }

        return $this->returnAjaxHtmlStanding($league, $weekNumber);
    }

    public function playAll(League $league)
    {
        $this->leagueService->playAll($league);

        return $this->returnAjaxHtmlStanding($league, ($league->teams()->count() - 1) * 2);
    }

    private function returnAjaxHtmlStanding(League $league, int $weekNumber): JsonResponse
    {
        $standings = $this->standingService->getByWeek($league, $weekNumber);
        $matches = $this->matchService->getMatchesByWeek($league, $weekNumber);
        $numberOfWeek = ($league->teams()->count() - 1) * 2;
        $predictions = $weekNumber >= ($numberOfWeek - 2) && $weekNumber != $numberOfWeek ?
            (new Prediction($league))->getPredictions() :
            [];

        return response()->json([
            'weekNumber' => $weekNumber,
            'standingTable' => view('components.standing-table', ['standings' => $standings])->render(),
            'matchesTable' => view('components.match-results', ['matchResults' => $matches, 'week' => $weekNumber])->render(),
            'predictions' => empty($predictions) ? '' : view('components.predictions', ['week' => $weekNumber, 'predictions' => $predictions])->render(),
        ]);
    }
}
