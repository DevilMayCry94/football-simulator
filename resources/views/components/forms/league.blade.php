<div class="form-group">
    <label for="league-name">Name</label>
    <input type="text" class="form-control" name="name" id="league-name" placeholder="League Name...">
</div>
<div class="form-group">
    <label for="league-description">Description</label>
    <textarea  class="form-control" name="description" id="league-description"></textarea>
</div>

<div class="form-group">
    <label for="league-teams">Teams</label>
    <a href="" class="btn btn-default add-team"><i class="fas fa-plus"></i>ADD Team</a>
{{--    <select class="form-control select2-add-teams" multiple type="text" name="teams[]"  id="league-teams">--}}
{{--    </select>--}}
    <div class="teams">
        <div class="form-group team row">
            <div class="col-8">
                <input type="text" class="form-control" name="team-name" placeholder="Team's name">
            </div>
            <div class="col-4">
                <input type="number" class="form-control" name="team-strength" min="0" max="10" placeholder="Team's strength">
            </div>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
