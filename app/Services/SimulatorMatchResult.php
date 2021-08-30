<?php

declare(strict_types=1);

namespace App\Services;


use App\Models\Enums\DifferentStrengthsEnum;
use App\Models\Team;
use Illuminate\Support\Facades\Storage;

class SimulatorMatchResult
{
    private Team $homeTeam;
    private Team $awayTeam;
    private array $predictions;

    public function __construct(Team $homeTeam, Team $awayTeam)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->predictions = json_decode(Storage::disk('rules')->get('graph.json'));
    }

    public function getHomeTeamScore(): int
    {
        return $this->getScore($this->getTeamScorePredictions($this->homeTeam, $this->awayTeam));
    }

    public function getAwayTeamScore(): int
    {
        return $this->getScore($this->getTeamScorePredictions($this->awayTeam, $this->homeTeam));
    }

    private function getScore(array $predictions): int
    {
        $values = $predictions;
        sort($values);
        $min = $values[0];
        $decimal = 1;
        if ($min < 1) {
            $str = (string) $min;
            $str = explode('.', $str)[1];
            $index = 0;
            $decimal = 10;
            while (true) {
                if ((int) $str[$index] > 0) {
                    break;
                }

                $index++;
                $decimal *= 10;
            }
        }

        $values = array_map(
            function ($value) use ($decimal) {
                return $value * $decimal;
            },
            $values
        );

        $rand = mt_rand((int) $min * $decimal, (int) array_sum($values));
        $rand /= $decimal;
        foreach ($predictions as $score => $weight) {
            $rand -= $weight;
            if ($rand <= 0) {
                return $score;
            }
        }

        return 0;
    }

    private function getDifferentStrength(Team $team1, Team $team2): int
    {
        return $team1->strength - $team2->strength;
    }

    private function getTeamScorePredictions(Team $scoredTeam, Team $concededTeam): array
    {
        $predictions = $this->predictions;
        $differentStrength  = $this->getDifferentStrength($scoredTeam, $concededTeam);
        if ($differentStrength >= DifferentStrengthsEnum::STRONG_DIFFERENT_STRENGTH) {
            return $predictions;
        }

        if (
            $differentStrength >= DifferentStrengthsEnum::AVERAGE_DIFFERENT_STRENGTH ||
            (
                $differentStrength < 0 &&
                abs($differentStrength) < DifferentStrengthsEnum::STRONG_DIFFERENT_STRENGTH
            )
        ) {
            return array_splice($predictions, 1);
        }

        if (
            $differentStrength >= DifferentStrengthsEnum::LOW_DIFFERENT_STRENGTH ||
            (
                $differentStrength < 0 &&
                abs($differentStrength) >= DifferentStrengthsEnum::STRONG_DIFFERENT_STRENGTH
            )
        ) {
            return array_splice($predictions, 2);
        }

        return $predictions;
    }
}
