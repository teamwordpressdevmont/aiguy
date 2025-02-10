@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Course</h2>
    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Name:</label>
        <input type="text" name="name" class="form-control" required>

        <label>Cover Image:</label>
        <input type="file" name="cover_image" class="form-control">

        <label>Logo:</label>
        <input type="file" name="logo" class="form-control">

        <label>Type:</label>
        <select name="type" class="form-control">
            <option value="free">Free</option>
            <option value="paid">Paid</option>
        </select>

        <label>Short Description:</label>
        <textarea name="short_description" class="form-control"></textarea>

        <label>Categories:</label>
        <select name="categories[]" multiple class="form-control">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-success mt-3">Save Course</button>
    </form>
</div>
@endsection
