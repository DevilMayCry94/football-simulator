<div class="mb-3">
    <label for="league-name">Name</label>
    <input type="text" class="form-control" name="name" id="league-name" placeholder="League Name...">
</div>
<div class="mb-3">
    <label for="league-description">Description</label>
    <textarea  class="form-control" name="description" id="league-description"></textarea>
</div>

<div class="mb-3">
    <label for="league-teams">Teams</label>
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

<div class="mb-3">
    <a href="" class="btn btn-default add-team"><i class="fas fa-plus"></i>ADD Team</a>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
