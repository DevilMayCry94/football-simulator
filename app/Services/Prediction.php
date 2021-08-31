<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Enums\PredictionResultsEnum;
use App\Models\Enums\ResultMatchPercentsEnum;
use App\Models\League;
use App\Models\Team;
use App\ValueObject\MatchEntity;

class Prediction
{
    private League $league;
    private MatchService $matchService;
    private StandingService $standingService;

    public function __construct(League $league)
    {
        $this->league = $league;
        $this->matchService = app()->make(MatchService::class);
        $this->standingService = app()->make(StandingService::class);
    }

    public function getPredictions(): array
    {
        $teams = $this->league->teams->keyBy('id');
        $teamChampionShipScenarios = array_fill_keys($teams->keys()->all(), 0);
        $matches = $this->matchService->getLeftMatchesFromWeek($this->league);

        if (count($matches) == 0) {
            return [];
        }

        $allScenarios = $this->getScenarios(count($matches));
        foreach ($allScenarios as $weekMatchResults) {
            $matchCollection = collect();
            foreach ($weekMatchResults as $index => $result) {
                $match = $matches->get($index);
                $scores = $this->getScoresByResult($result);
                $matchCollection->push(
                    new MatchEntity(
                        $match->homeTeam,
                        $match->awayTeam,
                        $match->week,
                        $scores[0],
                        $scores[1]
                    )
                );
            }

            $standings = $this->standingService->calculateStanding($this->league, $matchCollection, $this->league->current_week + 1);
            $teamChampionShipScenarios[$standings->first()->team_id]++;
        }

        $teamChampionShipChance = [];
        foreach ($teamChampionShipScenarios as $index => $championShipScenario) {
            $teamChampionShipChance[$index] = [
                'teamName' => $teams->get($index)->name,
                'chance' => $championShipScenario / count($allScenarios) * 100,
                'id' =>  $teams->get($index)->id,
            ];
        }

        return $teamChampionShipChance;
    }

    private function getScenarios(int $numberOfMatch): array
    {
        $possibleMatchResults = [];
        for ($i = 0; $i < $numberOfMatch; $i++) {
            $possibleMatchResults[] = [
                PredictionResultsEnum::HOME_WIN,
                PredictionResultsEnum::DRAW,
                PredictionResultsEnum::AWAY_WIN,
            ];
        }

        $index = 0;
        $results = [];
        return $this->recursive($possibleMatchResults, $index, $results);
    }

    private function recursive($array, &$index, $result): array
    {
        if (isset($array[1])) {
            foreach ($array[0] as $item) {
                $subArr = array_slice($array, 1);
                $numberOfScenario = pow(3, count($subArr));
                for($j = 0; $j < $numberOfScenario; $j++) {
                    $result[$index + $j][] = $item;
                }
                $result = $this->recursive($subArr, $index, $result);
            }
        } else {
            foreach ($array[0] as $item) {
                $result[$index++][] = $item;
            }
        }

        return $result;
    }

    private function getScoresByResult($result): array
    {
        //TODO calculate score with weight
        if ($result == PredictionResultsEnum::DRAW) {
            return [0, 0];
        }

        if ($result == PredictionResultsEnum::HOME_WIN) {
            return [1, 0];
        }

        return [0, 1];
    }
}
