<h6 class="lh-3">{{$week}} Week Match Results</h6>
<div class="container">
    @foreach($matchResults as $matchResult)
        <div class="row match-result" data-match-id="{{$matchResult->id}}">
            <div class="col-4">{{$matchResult->homeTeam->name}}</div>
            <div class="col-3">
                <div class="result row">
                    <div class="home-team-score col-4">{{$matchResult->home_team_score}}</div>
                    <div class="col-2">:</div>
                    <div class="home-team-score col-4">{{$matchResult->away_team_score}}</div>
                </div>
                <div class="form-row row edit-result d-none">
                    <div class="form-group col-md-5 p-0">
                        <input type="text" class="form-control p-375 home-score" value="{{$matchResult->home_team_score}}">
                    </div>
                    <div class="form-group col-md-1 p-0">:</div>
                    <div class="form-group col-md-5 p-0">
                        <input type="text" class="form-control p-375 away-score" value="{{$matchResult->away_team_score}}">
                    </div>
                </div>
            </div>
            <div class="col-4">{{$matchResult->awayTeam->name}}</div>
            <div class="col-1">
                <i class="fas fa-edit edit-result-btn"></i>
                <i class="fas fa-save save-result-btn d-none"></i>
            </div>
        </div>
    @endforeach
</div>
