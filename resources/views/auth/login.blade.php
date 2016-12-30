<form method="POST" role="form">
    <legend>Form Title</legend>

    <div class="form-group">
        <label for=""></label>
        <input type="email" name="email" placeholder="john.doe@email.com">
    </div>

    {!! csrf_field() !!}

    <button type="submit" class="btn btn-primary">Login</button>
</form>
