@extends('layouts.app')

<div class="container">
    <h2>Blog List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>User ID</th>
                <th>Featured Image</th>
                <th>Heading</th>
                <th>Reading Time</th>
                <th>Content</th>
            </tr>
        </thead>
        <tbody>
            @foreach($blog as $index => $blog)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $blog->user_id }}</td>
                <td>
                    @if($blog->featured_image)
                        <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Featured Image" width="100">
                    @else
                        No Image
                    @endif
                </td>
                <td>{{ $blog->heading }}</td>
                <td>{{ $blog->reading_time }}</td>
                <td>{{ $blog->content }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
