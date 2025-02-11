@extends('layouts.app')

@section('content')

    <!-- <h2>All Courses</h2>
    <a href="{{ route('courses.create') }}" class="btn btn-primary">Add Course</a> -->

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="flex">
        <h4 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">All Courses</h4>

        <a href="{{ route('courses.create') }}" class="ml-auto rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Add New 
        </a>            

    </div>    


    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <tr class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <th scope="col" class="px-6 py-3">Id</th>
            <th scope="col" class="px-6 py-3">Name</th>
            <th scope="col" class="px-6 py-3">logo</th>
            <th scope="col" class="px-6 py-3">Categories</th>
            <th scope="col" class="px-6 py-3">Type</th>
            <th scope="col" class="px-6 py-3">Cover Image</th>
            <th scope="col" class="px-6 py-3">Actions</th>
        </tr>
        @foreach($courses as $index =>  $course)
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
            <td class="px-6 py-4"> {{ $index + 1 }}</td>
            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $course->name }}</td>
            <td class="px-6 py-4">
                @if($course->logo)
                    <img src="{{ asset('storage/' . $course->logo) }}" alt="Logo" width="50">
                @else
                    No Logo
                @endif                    
            </td>
            <td class="px-6 py-4">
                @if($course->categoryCourses->isNotEmpty())
                    <span class="badge bg-info">{{ $course->categoryCourses->pluck('name')->join(', ') }}</span>
                @else
                    <span class="badge bg-info">No Category</span>
                @endif     
            </td>
            <td class="px-6 py-4">{{ ucfirst($course->type) }}</td>
            <td class="px-6 py-4">
                @if($course->cover_image)
                    <img src="{{ asset('storage/' . $course->cover_image) }}" alt="Cover" width="50">
                @else
                    No Cover Image
                @endif                    
            </td>
            <td class="px-6 py-4">
                <div class="flex gap-3">
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning btn-sm">
                        <svg fill="#0D0D0D" width="20px" height="20px" viewBox="0 0 36 36" version="1.1"  preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>edit</title>
                            <path class="clr-i-outline clr-i-outline-path-1" d="M33.87,8.32,28,2.42a2.07,2.07,0,0,0-2.92,0L4.27,23.2l-1.9,8.2a2.06,2.06,0,0,0,2,2.5,2.14,2.14,0,0,0,.43,0L13.09,32,33.87,11.24A2.07,2.07,0,0,0,33.87,8.32ZM12.09,30.2,4.32,31.83l1.77-7.62L21.66,8.7l6,6ZM29,13.25l-6-6,3.48-3.46,5.9,6Z"></path>
                            <rect x="0" y="0" width="36" height="36" fill-opacity="0"/>
                        </svg>
                    </a>
                    <a href="{{ route('courses.delete', $course->id) }}" onclick="return confirm('Are you sure you want to delete this tool?');">
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <title>delete</title>
                        <path d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z" fill="red"/></svg>
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </table>

@endsection
