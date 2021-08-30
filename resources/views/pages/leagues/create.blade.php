@extends('layouts.base')
@section('content')
    <h3>Create League</h3>
    <div class="row">
        @if($errors->any())
            {!! implode('', $errors->all('<div class="text-red">:message</div>')) !!}
        @endif
        <form action="{{ route('leagues.store') }}" method="POST" class="add-league">
            {{ csrf_field() }}
            @include('components.forms.league')
        </form>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.add-team', function (e) {
                e.preventDefault();
                let addTeamBlock = document.createElement('div');
                let teamNameBlock = document.createElement('div');
                let teamStrengthBlock = document.createElement('div');
                let teamNameInput = document.createElement('input');
                let teamStrengthInput = document.createElement('input');

                addTeamBlock.className = 'form-group team row';
                teamNameBlock.className = 'col-8';
                teamStrengthBlock.className = 'col-4';
                teamNameInput.className = 'form-control';
                teamStrengthInput.className = 'form-control';
                teamNameInput.type = 'text';
                teamNameInput.placeholder = 'Team\'s name';
                teamStrengthInput.placeholder = 'Team\'s strength';
                teamStrengthInput.type = 'number';
                teamNameInput.name = 'team-name';
                teamStrengthInput.name = 'team-strength';
                teamStrengthInput.max = '10';
                teamStrengthInput.min = '1';

                teamNameBlock.appendChild(teamNameInput);
                teamStrengthBlock.appendChild(teamStrengthInput);
                addTeamBlock.appendChild(teamNameBlock);
                addTeamBlock.appendChild(teamStrengthBlock);

                $('.teams').append(addTeamBlock);
            });

            $(document).on('submit', '.add-league', function (e) {
                let teams = [];
                $('.teams .team').each(function (index, element) {

                    const teamName = $(element).find('input[name="team-name"]').val();
                    const teamStrength = $(element).find('input[name="team-strength"]').val();
                    teams.push({"name": teamName, teamStrength: teamStrength});
                });

                let teamsInput = document.createElement('input');
                teamsInput.type = 'hidden';
                teamsInput.value = JSON.stringify(teams);
                teamsInput.name = 'teams';
                $(this).append(teamsInput);
            });
        });
    </script>
@endsection
