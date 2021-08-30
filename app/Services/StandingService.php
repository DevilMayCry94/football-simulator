<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Enums\MatchResultPointsEnum;
use App\Models\League;
use App\Models\Standing;
use App\Models\Team;
use App\ValueObject\MatchEntity;
use Illuminate\Database\Eloquent\Collection;

class StandingService extends CrudBaseService
{
    public function getModel(): string
    {
        return Standing::class;
    }

    public function createStartStanding(League $league): void
    {
        foreach ($league->teams as $index => $team) {
            $this->create([
                'position' => $index + 1,
                'week' => 0,
                'league_id' => $league->id,
                'team_id' => $team->id,
            ]);
        }
    }

    public function calculateStanding(League $league, \Illuminate\Support\Collection $matchResults, int $weekNumber): void
    {
        $currentStanding = $this->getQueryBuilder()->where([
            'league_id' => $league->id,
            'week' => $weekNumber - 1,
        ])->get()->keyBy('team_id');

        /** @var MatchEntity $matchResult */
        foreach ($matchResults as $matchResult) {
            $homeTeamStanding = $currentStanding->get($matchResult->getHomeTeam()->id);
            $awayTeamStanding = $currentStanding->get($matchResult->getAwayTeam()->id);

            $homeTeamStanding->week += 1;
            $awayTeamStanding->week += 1;

            $homePoints = $this->getPointsByScores($matchResult->getHomeScore(), $matchResult->getAwayScore());
            $awayPoints = $this->getPointsByScores($matchResult->getAwayScore(), $matchResult->getHomeScore());
            $homeTeamStanding = $this->updateStanding($homeTeamStanding, $homePoints);
            $awayTeamStanding = $this->updateStanding($awayTeamStanding, $awayPoints);

            $this->updateGoalStanding($homeTeamStanding, $matchResult->getHomeScore(), $matchResult->getAwayScore());
            $this->updateGoalStanding($awayTeamStanding, $matchResult->getAwayScore(), $matchResult->getHomeScore());
        }

        $this->updateStandingPositions($currentStanding);
    }

    private function updateStandingPositions(Collection $standings): void
    {
        $standings = $standings->sort(function ($standing1, $standing2) {
            if ($standing1->points == $standing2->points) {
                if ($standing1->goal_difference == $standing2->goal_difference) {
                    if ($standing1->goal_for == $standing2->goal_for) {
                        //TODO if it is last week, add additional match
                        return $standing1->team->strength > $standing2->team->strength ? -1 : 1;
                    } else {
                        return $standing1->goal_for == $standing2->goal_for ? -1 : 1;
                    }
                } else {
                    return $standing1->goal_difference > $standing2->goal_difference ? -1 : 1;
                }
            }

            return $standing1->points > $standing2->points ? -1 : 1;
        });

        $position = 1;
        foreach ($standings as $standing) {
            $this->create([
                'position' => $position++,
                'week' => $standing->week,
                'league_id' => $standing->league_id,
                'team_id' => $standing->team_id,
                'goal_for' => $standing->goal_for,
                'goal_against' => $standing->goal_against,
                'goal_difference' => $standing->goal_difference,
                'win' => $standing->win,
                'lost' => $standing->lost,
                'draw' => $standing->draw,
                'points' => $standing->points,
            ]);
        }
    }

    private function updateStanding(Standing $standing, int $points): Standing
    {
        $standing->points = $standing->points + $points;
        $standing->win = $standing->win + ($points == MatchResultPointsEnum::WIN ? 1 : 0);
        $standing->draw = $standing->draw + ($points == MatchResultPointsEnum::DRAW ? 1 : 0);
        $standing->lost = $standing->lost + ($points == MatchResultPointsEnum::LOST ? 1 : 0);

        return $standing;
    }

    private function updateGoalStanding(Standing $standing, int $goalFor, int $goalAgainst): Standing
    {
        $standing->goal_for = $goalFor;
        $standing->goal_against = $goalAgainst;
        $standing->goal_difference = $standing->goal_difference + ($goalFor - $goalAgainst);

        return $standing;
    }

    private function getPointsByScores(int $team1Score, int $team2Score): int
    {
        if ($team1Score == $team2Score) {
            return MatchResultPointsEnum::DRAW;
        }

        if ($team1Score > $team2Score) {
            return MatchResultPointsEnum::WIN;
        }

        return MatchResultPointsEnum::LOST;
    }
}
