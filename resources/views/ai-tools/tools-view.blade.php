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