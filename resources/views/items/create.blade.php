<form action="{{ route('items.store') }}" method="post" role="form" enctype="multipart/form-data">
    <legend>ADD SOME DATA</legend>

    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="title" placeholder="The title of your item">
    </div>

    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="slug" placeholder="Optional">
    </div>

    <div class="form-group">
        <label for="">
            <textarea name="description" cols="30" rows="10"></textarea>
        </label>
    </div>

    <div class="form-group">

        <label for="">
            <input type="file" name="images[]"></label><label for="">
            <input type="file" name="images[]">
        </label>

        <label for="">
            <input type="file" name="images[]"></label>

        <label for=""><input type="file"
                             name="images[]"></label>

        <label for=""><input type="file" name="images[]"></label>
    </div>

    {!! csrf_field() !!}

    <button type="submit" class="btn btn-primary">Add Item</button>
</form>
