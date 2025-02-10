@extends('layouts.app')


@section('content')

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">View AI Tool</h1>
    </div>

    <div class="mt-5 mx-auto max-w-7xl bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mx-auto">
        <img class="rounded-t-lg w-full h-100 object-cover object-center -mb-12" src="{{ asset('storage/' . $tool->cover) }}" alt="" />
        <div class="flex flex-col items-center pb-10">
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ asset('storage/' . $tool->logo) }}" alt="Bonnie image"/>
            <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $tool->name }}</h5>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $tool->category->name ?? 'Uncategorized' }}</span>
            <div class="flex mt-4 md:mt-6">
            </div>
        </div>
    </div>
    <div class="mx-auto max-w-7xl py-6">
    <a href="{{ route('ai-tools.list') }}" class=" rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Back To List
    </a>
    </div>

@endsection
