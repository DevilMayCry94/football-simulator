@extends('layouts.base')
@section('content')
    <h2>{{ $league->name }}</h2>
    <div class="row">
        <div class="col-6">
            <h3>League Table</h3>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Team</th>
                    <th scope="col">PTS</th>
                    <th scope="col">P</th>
                    <th scope="col">W</th>
                    <th scope="col">D</th>
                    <th scope="col">L</th>
                    <th scope="col">GD</th>
                </tr>
                </thead>
                <tbody>
                @foreach($league->currentStanding() as $index => $stand)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $stand->team->name }}</td>
                        <td>{{ $stand->points }}</td>
                        <td>{{ $stand->week }}</td>
                        <td>{{ $stand->win }}</td>
                        <td>{{ $stand->draw }}</td>
                        <td>{{ $stand->lost }}</td>
                        <td>{{ $stand->goal_difference }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="row justify-content-between">
                <div class="col-4">
                    <a href="{{ route('leagues.play-all', $league) }}" class="btn btn-secondary pull-left">Play All</a>
                </div>
                <div class="col-5">
                    <a href="{{ route('leagues.next-week', $league) }}" class="btn btn-success pull-right">Next Week</a>
                </div>
            </div>
        </div>
        <div class="col-6">
            <h3>Match Results</h3>
            <h6 class="lh-3">{{$league->current_week}} Week Match Results</h6>
            <div class="container">
                @foreach($league->currentWeekMatches() as $matchResult)
                    <div class="row">
                        <div class="col-4">{{$matchResult->homeTeam->name}}</div>
                        <div class="col-1"></div>
                        <div class="col-2">{{$matchResult->home_team_score}}:{{$matchResult->away_team_score}}</div>
                        <div class="col-1"></div>
                        <div class="col-4">{{$matchResult->awayTeam->name}}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
