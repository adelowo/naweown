<form method="POST" role="form">
    <legend>Form Title</legend>

    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="name" placeholder="...">
    </div>
    <div class="form-group">
        <label for=""></label>
        <input type="email" class="form-control" name="email" placeholder="...">
    </div>
    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="moniker" placeholder="...">
    </div>

    {!! csrf_field() !!}

    <button type="submit" class="btn btn-primary">Create Account</button>
</form>
