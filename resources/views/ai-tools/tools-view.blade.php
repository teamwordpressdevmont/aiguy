@extends('layouts.app')



@if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                        #
                        </th>                
                        <th scope="col" class="px-6 py-3">
                        Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                        Slug
                        </th>
                        <th scope="col" class="px-6 py-3">
                        Category
                        </th>
                        <th scope="col" class="px-6 py-3">
                        Logo
                        </th>
                        <th scope="col" class="px-6 py-3">
                        Cover
                        </th>
                        <th scope="col" class="px-6 py-3">
                        Action
                        </th>                        
                    </tr>
                </thead>
                <tbody>          
                @foreach($aiTools as $index => $tool)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $index + 1 }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $tool->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $tool->slug }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $tool->category->name ?? 'Uncategorized' }}
                        </td>
                                
                        <td class="px-6 py-4">
                            @if($tool->logo)
                                <img src="{{ asset('storage/' . $tool->logo) }}" alt="Logo" width="50">
                            @else
                                No Logo
                            @endif                    
                        </td>
                        <td class="px-6 py-4">
                            @if($tool->cover)
                                <img src="{{ asset('storage/' . $tool->cover) }}" alt="Cover" width="100">
                            @else
                                No Cover
                            @endif
                        </td>    
                        <td class="px-6 py-4">
                            <div class="flex gap-4"">
                                <a href="#">
                                    <svg fill="#0D0D0D" width="20px" height="20px" viewBox="0 0 36 36" version="1.1"  preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>edit-line</title>
                                        <path class="clr-i-outline clr-i-outline-path-1" d="M33.87,8.32,28,2.42a2.07,2.07,0,0,0-2.92,0L4.27,23.2l-1.9,8.2a2.06,2.06,0,0,0,2,2.5,2.14,2.14,0,0,0,.43,0L13.09,32,33.87,11.24A2.07,2.07,0,0,0,33.87,8.32ZM12.09,30.2,4.32,31.83l1.77-7.62L21.66,8.7l6,6ZM29,13.25l-6-6,3.48-3.46,5.9,6Z"></path>
                                        <rect x="0" y="0" width="36" height="36" fill-opacity="0"/>
                                    </svg>                            
                                </a>
                                <a href="#">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4V4zm2 2h6V4H9v2zM6.074 8l.857 12H17.07l.857-12H6.074zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1z" fill="red"/></svg>
                                </a>
                                <a href="#">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" fill="blue"/><path d="M21.894 11.553C19.736 7.236 15.904 5 12 5c-3.903 0-7.736 2.236-9.894 6.553a1 1 0 0 0 0 .894C4.264 16.764 8.096 19 12 19c3.903 0 7.736-2.236 9.894-6.553a1 1 0 0 0 0-.894zM12 17c-2.969 0-6.002-1.62-7.87-5C5.998 8.62 9.03 7 12 7c2.969 0 6.002 1.62 7.87 5-1.868 3.38-4.901 5-7.87 5z" fill="blue"/></svg> 
                                </a>
                            </div>
                        </td>                
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


<!-- <div class="container">
    <h2>AI Tools List</h2>



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
</div> -->