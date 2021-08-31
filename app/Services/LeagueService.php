<?php

declare(strict_types=1);

namespace App\Services;


use App\Events\LeagueCreatedEvent;
use App\Models\League;
use App\ValueObject\MatchEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeagueService extends CrudBaseService
{
    private MatchService $matchService;
    private TeamService $teamService;
    private StandingService $standingService;

    public function __construct(MatchService $matchService, TeamService $teamService, StandingService $standingService)
    {
        $this->matchService = $matchService;
        $this->teamService = $teamService;
        $this->standingService = $standingService;
    }
    public function getModel(): string
    {
        return League::class;
    }

    public function getList(): LengthAwarePaginator
    {
        return $this->getQueryBuilder()->paginate(10);
    }

    public function create(array $data): ?Model
    {
        DB::beginTransaction();
        try {
            /** @var League $league */
            $league = parent::create($data);
            $teams = new Collection();
            $teamsData = json_decode($data['teams'], true);
            foreach ($teamsData as $team) {
                $teams->push($this->teamService->create(
                    [
                        'league_id' => $league->id,
                        'name' => $team['name'],
                        'strength' => $team['teamStrength'],
                    ]
                ));
            }

            $this->standingService->createStartStanding($league);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getTraceAsString());
            Log::error($exception->getMessage());
            return null;
        }

        LeagueCreatedEvent::dispatch($league);
        return $league;
    }

    public function nextWeek(League $league, int $weekNumber): bool
    {
        DB::beginTransaction();
        try {
            $matches = $this->matchService->getMatchesByWeek($league, $weekNumber);
            $results = collect();
            foreach ($matches as $match) {
                $simulator = new SimulatorMatchResult($match->homeTeam, $match->awayTeam);
                $match->home_team_score = $simulator->getHomeTeamScore();
                $match->away_team_score = $simulator->getAwayTeamScore();
                $match->save();
                $results->push(
                    new MatchEntity(
                        $match->homeTeam,
                        $match->awayTeam,
                        $weekNumber,
                        $match->home_team_score,
                        $match->away_team_score
                    )
                );
            }

            $standings = $this->standingService->calculateStanding($league, $results, $weekNumber);
            $this->standingService->updateStandingPosition($standings);
            $this->update($league->id, ['current_week' => $weekNumber]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());

            return false;
        }

        return true;
    }

    public function playAll(League $league): bool
    {
        $currentWeek = $league->current_week;
        for ($i = $currentWeek + 1; $i <= ($league->teams()->count() - 1) * 2; $i ++) {
            $this->nextWeek($league, $i);
        }

        return true;
    }
}
