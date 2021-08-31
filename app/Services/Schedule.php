<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Team;
use App\ValueObject\MatchEntity;
use Illuminate\Support\Collection;

class Schedule
{
    private Collection $teams;

    public function __construct(Collection $teams)
    {
        $this->teams = $teams;
    }

    public function generate(): array
    {
        $countWeek = ($this->teams->count() - 1) * 2;
        $matches = $this->getTeamConfrontation();

        $weeks = [];
        $playedMatches = [];
        for ($i = 1; $i <= ceil($countWeek / 2); $i++) {
            $currentWeekTeams = [];
            foreach ($matches as $index => $match) {
                list($team1, $team2) = $match;
                if (
                    !in_array($team1, $currentWeekTeams) &&
                    !in_array($team2, $currentWeekTeams) &&
                    !in_array($index, $playedMatches)
                ) {
                    $weeks[$i][] = new MatchEntity($team1, $team2, $i);
                    $playedMatches[] = $index;
                    $currentWeekTeams[] = $team1;
                    $currentWeekTeams[] = $team2;
                }
            }
        }

        for ($i = count($weeks) + 1; $i <= $countWeek; $i++) {
            /** @var MatchEntity $match */
            foreach ($weeks[$countWeek + 1 - $i] as $match) {
                $weeks[$i][] = new MatchEntity($match->getAwayTeam(), $match->getHomeTeam(), $i);
            }
        }

        return $weeks;
    }

    private function getTeamConfrontation(): array
    {
        $matches = [];
        for ($i = 0; $i < $this->teams->count(); $i++) {
            for ($j = $i + 1; $j < $this->teams->count(); $j++) {
                if (!$this->teams->get($i) instanceof Team || !$this->teams->get($j) instanceof Team) {
                    continue;
                }
                $matches[] = [$this->teams->get($i), $this->teams->get($j)];
            }
        }

        return $matches;
    }
}
