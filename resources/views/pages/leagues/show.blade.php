@extends('layouts.base')
@section('content')
    <h2>{{ $league->name }}</h2>
    <div class="row position-relative">
        <div class="text-center spinner position-absolute top-0 h-100 w-100 d-flex justify-content-center align-items-center invisible">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="col-4">
            <h3>League Table</h3>
            <div class="standing" data-current-week="{{$league->current_week}}" data-league-id="{{$league->id}}">
                @include('components.standing-table', ['standings' => $league->currentStanding()])
            </div>
            <div class="row justify-content-between">
                <div class="col-3 p-0">
                    <a href="" class="btn btn-secondary pull-left play-all">Play All</a>
                </div>
                <div class="col-5">
                    <a href="" class="btn btn-info pull-right previous-week">Previous Week</a>
                </div>
                <div class="col-4 p-0">
                    <a href="" class="btn btn-success pull-right next-week">Next Week</a>
                </div>
            </div>
        </div>
        <div class="col-4">
            <h3>Match Results</h3>
            <div class="match-results">
                @include('components.match-results', ['matchResults' => $league->currentWeekMatches(), 'week' => $league->current_week])
            </div>

        </div>
        <div class="col-4 predictions">
        @if (!empty($predictions))
                @include('components.predictions', ['week' => $league->current_week])
        @endif
        </div>
    </div>
@endsection
