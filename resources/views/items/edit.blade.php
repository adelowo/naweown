<form action="{{ route('items.update', $item->id) }}" method="post" role="form" enctype="multipart/form-data">
    <legend>Update</legend>

    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="title" value="{{ $item->title }}">
    </div>

    <div class="form-group">
        <label for=""></label>
        <input type="text" class="form-control" name="slug" value="{{ $item->slug }}">
    </div>

    <div class="form-group">
        <label for="">
            <textarea name="description" cols="30" rows="10">{{ $item->description }}</textarea>
        </label>
    </div>

    <div class="form-group">

        <div style="color: red">

            <p>Any change you make to the files is going to overwrite whatever you have defined
                previously
            </p>
            <p>
                Fields that are labelled * already contain a value
            </p>

        </div>


        <label for="">
            <input type="file" name="images[]">
        </label>

        <label for="">
            <input type="file" name="images[]">
        </label>

        <label for="">
            <input type="file" name="images[]"></label>

        <label for=""><input type="file"
                             name="images[]"></label>

        <label for=""><input type="file" name="images[]"></label>
    </div>

    {!! csrf_field() !!}

    {{ method_field('PUT') }}

    <button type="submit" class="btn btn-primary">Update</button>
</form>

@if($errors->count())
    {{ var_dump($errors->all()) }}
@endif