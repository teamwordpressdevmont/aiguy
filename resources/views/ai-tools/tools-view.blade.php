@extends('layouts.app')

<div class="container">
    <h2>AI Tools List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Category</th>
                <th>Logo</th>
                <th>Cover</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aiTools as $index => $tool)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $tool->name }}</td>
                <td>{{ $tool->slug }}</td>
                <td>{{ $tool->category->name ?? 'Uncategorized' }}</td>
                <td>
                    @if($tool->logo)
                        <img src="{{ asset('storage/' . $tool->logo) }}" alt="Logo" width="50">
                    @else
                        No Logo
                    @endif
                </td>
                <td>
                    @if($tool->cover)
                        <img src="{{ asset('storage/' . $tool->cover) }}" alt="Cover" width="100">
                    @else
                        No Cover
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>