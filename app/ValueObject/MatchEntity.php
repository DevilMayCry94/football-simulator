<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Models\Team;

class MatchEntity
{
    private Team $homeTeam;
    private Team $awayTeam;
    private ?int $week;
    private ?int $homeScore;
    private ?int $awayScore;
    private int $leagueId;

    public function __construct(Team $homeTeam, Team $awayTeam, ?int $week, ?int $homeScore = 0, ?int $awayScore = 0)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->week = $week;
        $this->homeScore = $homeScore;
        $this->awayScore = $awayScore;

        $this->leagueId = $homeTeam->league_id;
    }

    public function getWeek(): int
    {
        return $this->week;
    }

    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    public function getLeagueId(): int
    {
        return $this->leagueId;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }
}
