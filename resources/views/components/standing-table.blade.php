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
    @foreach($standings as $index => $stand)
        <tr>
            <th scope="row">{{ $index + 1 }}</th>
            <td class="text-start">{{ $stand->team->name }}</td>
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
