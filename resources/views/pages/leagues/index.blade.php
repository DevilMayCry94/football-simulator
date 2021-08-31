@extends('layouts.base')
@section('content')
    <div class="container league-page">
        <div class="row w-100 mb-4">
            <a href="{{route('leagues.create')}}" class="btn btn-success"><i class="fas pa-plus"></i>Add League</a>
        </div>
        <div class="row">
            @foreach($leagues as $league)
                <div class="col-3">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title text-center">{{ $league->name }}</h5>
                            <p class="card-text">{{ $league->description }}</p>
                            <p><i class="fas fa-users"></i>{{ $league->teams->count() }}</p>
                            <a href="{{ route('leagues.show', $league) }}" class="btn btn-primary">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
