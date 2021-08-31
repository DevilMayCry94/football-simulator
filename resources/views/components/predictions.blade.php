<h3>{{$week}} WeekPredictions for Championship</h3>
<div class="container">
    @foreach($predictions as $prediction)
        <div class="row justify-content-between">
            <div class="col-6 text-start">{{$prediction['teamName']}}</div>
            <div class="col-3">{{number_format($prediction['chance'], 2)}}</div>
        </div>
    @endforeach
</div>
