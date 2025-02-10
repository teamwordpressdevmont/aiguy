@extends('layouts.app')

    <h1></h1>







    <div class="mt-5 mx-auto max-w-7xl bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mx-auto">
        <div class="flex flex-col items-center pb-10">
<<<<<<< Updated upstream
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $blog->category->name ?? 'Uncategorized' }}</span>
=======
>>>>>>> Stashed changes
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ asset('storage/' . $blog->featured_image) }}" alt="Featured image"/>
            <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $blog->heading }}</h5>
            <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $blog->reading_time }}</h5>
            <p class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $blog->content }}</p>
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ asset('storage/' . $blog->left_image) }}" alt="Left image"/>
            <p class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $blog->right_text }}</p>
            <p class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $blog->middle_text }}</p>
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ asset('storage/' . $blog->middle_image) }}" alt="Left image"/>
            <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $blog->sub_title }}</h5>
            <p class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $blog->sub_content }}</p>
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ asset('storage/' . $blog->sub_image) }}" alt="Left image"/>




            <div class="flex mt-4 md:mt-6">
            </div>
        </div>
    </div>
