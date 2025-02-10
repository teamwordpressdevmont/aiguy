@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Course</h2>
    <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Name:</label>
        <input type="text" name="name" value="{{ $course->name }}" class="form-control" required>

        <label>Cover Image:</label>
        <input type="file" name="cover_image" class="form-control">

        <label>Logo:</label>
        <input type="file" name="logo" class="form-control">

        <label>Type:</label>
        <select name="type" class="form-control">
            <option value="free" {{ $course->type == 'free' ? 'selected' : '' }}>Free</option>
            <option value="paid" {{ $course->type == 'paid' ? 'selected' : '' }}>Paid</option>
        </select>

        <label>Short Description:</label>
        <textarea name="short_description" class="form-control">{{ $course->short_description }}</textarea>

        <label>Categories:</label>
        <select name="categories[]" multiple class="form-control">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ in_array($category->id, $course->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-success mt-3">Update Course</button>
    </form>
</div>
@endsection
