@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Courses</h2>
    <a href="{{ route('courses.create') }}" class="btn btn-primary">Add Course</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Categories</th>
            <th>Actions</th>
        </tr>
        @foreach($courses as $course)
        <tr>
            <td>{{ $course->name }}</td>
            <td>{{ ucfirst($course->type) }}</td>
            <td>
                @foreach($course->categories as $category)
                    <span class="badge bg-info">{{ $category->name }}</span>
                @endforeach
            </td>
            <td>
                <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
