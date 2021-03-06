<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeagueCreateRequest;
use App\Models\League;
use App\Services\LeagueService;
use App\Services\Prediction;
use Illuminate\Http\Request;
use Auth;

class LeagueController extends Controller
{
    private LeagueService $leagueService;

    public function __construct(LeagueService $leagueService)
    {
        $this->leagueService = $leagueService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.leagues.index')
            ->withLeagues($this->leagueService->getList());
    }

    public function create()
    {
        return view('pages.leagues.create');
    }

    public function store(LeagueCreateRequest $request)
    {
        if (!$league = $this->leagueService->create(array_merge($request->validated(), ['user_id' => Auth::user()->id]))) {
            return redirect()->back();
        }

        return redirect(route('leagues.show', $league));
    }

    public function show(League $league)
    {
        $numberOfWeek = ($league->teams()->count() - 1) * 2;
        $predictions = $league->current_week >= ($numberOfWeek- 2) && $league->current_week < $numberOfWeek ?
            (new Prediction($league))->getPredictions() :
            [];

        return view('pages.leagues.show')
            ->withLeague($league)
            ->withPredictions($predictions);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function nextWeek(League $league)
    {
        $this->leagueService->nextWeek($league, $league->current_week + 1);
        return redirect(route('leagues.show', $league));
    }
}
