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
@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.next-week', function (e) {
                e.preventDefault();
                let currentWeek = $('.standing').data('current-week');
                getData(parseInt(currentWeek) + 1);
            });

            $(document).on('click', '.previous-week', function (e) {
                e.preventDefault();
                let currentWeek = $('.standing').data('current-week');
                getData(parseInt(currentWeek) - 1);
            });

            let getData = (weekNumber) => {
                $('.spinner').removeClass('invisible');
                let leagueId = $('.standing').data('league-id');
                $.get('/leagues/'+leagueId+'/week/'+weekNumber, function (res) {
                    $('.standing').data('current-week', res.weekNumber);
                    $('.standing').empty().append(res.standingTable);
                    $('.match-results').empty().append(res.matchesTable);
                    $('.spinner').addClass('invisible');
                    if (res.predictions) {console.log(res.predictions);
                        $('.predictions').empty().append(res.predictions);
                    }
                });
            }

            $(document).on('click', '.play-all', function (e) {
                e.preventDefault();
                let leagueId = $('.standing').data('league-id');
                $.get('/leagues/'+leagueId+'/play-all', function (res) {
                    $('.standing').data('current-week', res.weekNumber);
                    $('.standing').empty().append(res.standingTable);
                    $('.match-results').empty().append(res.matchesTable);
                });
            });

            $(document).on('click', '.edit-result-btn', function (e) {
                e.preventDefault();
                const parent = $(this).closest('.match-result');
                parent.find('.result').addClass('d-none');
                parent.find('.edit-result').removeClass('d-none');
                parent.find('.save-result-btn').removeClass('d-none');
                $(this).addClass('d-none');
            });

            $(document).on('click', '.save-result-btn', function (e) {
                e.preventDefault();
                $('.spinner').removeClass('invisible');
                const parent = $(this).closest('.match-result');
                const matchId = parent.data('match-id');
                const data = {
                    home_team_score: parent.find('.edit-result .home-score').val(),
                    away_team_score: parent.find('.edit-result .away-score').val(),
                    _method: 'PUT',
                    _token: $('meta[name="token"]').attr('content')
                }

                $.post('/matches/'+matchId, data)
                    .done(function (res) {
                        if (res.success) {
                            const currentWeek = $('.standing').data('current-week');
                            getData(currentWeek);
                        }
                    });

                parent.find('.result').removeClass('d-none');
                parent.find('.edit-result').addClass('d-none');
                parent.find('.edit-result-btn').removeClass('d-none');
                $(this).addClass('d-none');
            });
        });
    </script>
@endsection
