<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\League;
use App\Models\Match;
use App\ValueObject\MatchEntity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchService extends CrudBaseService
{
    public function getModel(): string
    {
        return Match::class;
    }

    public function generateSchedule(Collection $teams): bool
    {
        try {
            DB::beginTransaction();
            $scheduleService = new Schedule($teams);
            foreach ($scheduleService->generate() as $matches) {
                /** @var MatchEntity $match */
                foreach ($matches as $match) {
                    $this->create(
                        [
                            'week' => $match->getWeek(),
                            'league_id' => $match->getLeagueId(),
                            'home_team_id' => $match->getHomeTeam()->id,
                            'away_team_id' => $match->getAwayTeam()->id,
                        ]
                    );
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getTraceAsString());
            Log::error($exception->getMessage());
            return false;
        }

        return true;
    }

    public function getMatchesByWeek(League $league, int $weekNumber): Collection
    {
        return $this->getQueryBuilder()->where(
            [
                'league_id' => $league->id,
                'week' => $weekNumber,
            ]
        )->get();
    }
}
