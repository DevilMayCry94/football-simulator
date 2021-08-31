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
