<<<<<<< Updated upstream
@extends('layouts.app')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tiny.cloud/1/nqaglkyao4d08whpvjbu820xk982n76q6yagitulzkuyphzw/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
        <script>
            tinymce.init({
                selector: '.tinymce-editor', // Replace this CSS selector to match the placeholder element for TinyMCE
                plugins: 'code table lists',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
            });
=======

<<<<<<<< Updated upstream:resources/views/partials/header.blade.php
@php
    $currentController = class_basename(Route::current()->controller); // Gets current controller name
@endphp

@if($currentController == 'AiToolDataController' || $currentController == 'AiToolCategoryController')
    <div class="">
========
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Place the first <script> tag in your HTML's <head> -->
        <script src="https://cdn.tiny.cloud/1/nqaglkyao4d08whpvjbu820xk982n76q6yagitulzkuyphzw/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

        <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
        <script>
          tinymce.init({
            selector: 'textarea',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
          });
>>>>>>> Stashed changes
        </script>
    <title>Blog Submission</title>

</head>

<body class="">


    <div class="min-h-full">
<<<<<<< Updated upstream
=======
>>>>>>>> Stashed changes:resources/views/blog/index.blade.php
>>>>>>> Stashed changes
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <img class="size-8"
<<<<<<< Updated upstream
                                src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=500"
=======
                                src="http://127.0.0.1:8000/storage/ai-tools-images/ai-tools-logo-dev-vc-logo-2025-02-10-082903.png"
>>>>>>> Stashed changes
                                alt="Your Company">
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
<<<<<<< Updated upstream
                                <a href="#"
                                    class="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white"
                                    aria-current="page">Dashboard</a>
                                <a href="#"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Team</a>
                                <a href="#"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Projects</a>
                                <a href="#"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Calendar</a>
                                <a href="#"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Reports</a>
=======
                                <a href="{{ route('ai-tools.index') }}"
                                class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Ai Tool</a>
                            <a href="{{ route('ai-tools.list') }}"
                                class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Ai Tool List</a>
>>>>>>> Stashed changes
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <button type="button"
                                class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                                <span class="absolute -inset-1.5"></span>
                                <span class="sr-only">View notifications</span>
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" aria-hidden="true" data-slot="icon">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                </svg>
                            </button>

                            <!-- Profile dropdown -->
                            <div class="relative ml-3">
                                <div>
                                    <button type="button"
                                        class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden"
                                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="absolute -inset-1.5"></span>
                                        <span class="sr-only">Open user menu</span>
                                        <img class="size-8 rounded-full"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                            alt="">
                                    </button>
                                </div>

                                <!-- <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 ring-1 shadow-lg ring-black/5 focus:outline-hidden"
                                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                    tabindex="-1">

                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem"
                                        tabindex="-1" id="user-menu-item-0">Your Profile</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem"
                                        tabindex="-1" id="user-menu-item-1">Settings</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem"
                                        tabindex="-1" id="user-menu-item-2">Sign out</a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <button type="button"
                            class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden"
                            aria-controls="mobile-menu" aria-expanded="false">
                            <span class="absolute -inset-0.5"></span>
                            <span class="sr-only">Open main menu</span>
                            <!-- Menu open: "hidden", Menu closed: "block" -->
                            <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true" data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <!-- Menu open: "block", Menu closed: "hidden" -->
                            <svg class="hidden size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true" data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div class="md:hidden" id="mobile-menu">
                <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
<<<<<<< Updated upstream
                    <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                    <a href="#" class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white"
                        aria-current="page">Dashboard</a>
                    <a href="#"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Team</a>
                    <a href="#"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Projects</a>
                    <a href="#"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Calendar</a>
                    <a href="#"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Reports</a>
=======
                  
                    <a href="{{ route('ai-tools.index') }}"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">AI Tool</a>
                    <a href="{{ route('ai-tools.list') }}"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">AI Tool List</a>
                        
                 
>>>>>>> Stashed changes
                </div>
                <div class="border-t border-gray-700 pt-4 pb-3">
                    <div class="flex items-center px-5">
                        <div class="shrink-0">
                            <img class="size-10 rounded-full"
                                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="">
                        </div>
                        <div class="ml-3">
                            <div class="text-base/5 font-medium text-white">Tom Cook</div>
                            <div class="text-sm font-medium text-gray-400">tom@example.com</div>
                        </div>
                        <button type="button"
                            class="relative ml-auto shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">View notifications</span>
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true" data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <a href="#"
                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Your
                            Profile</a>
                        <a href="#"
                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Settings</a>
                        <a href="#"
                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Sign
                            out</a>
                    </div>
                </div>
            </div>
        </nav>
<<<<<<< Updated upstream
        <header class="bg-white shadow-sm">
           <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
              <h1 class="text-3xl font-bold tracking-tight text-gray-900">Add Blog</h1>
           </div>
        </header>
    </div>
=======
    </div>
<<<<<<<< Updated upstream:resources/views/partials/header.blade.php
@endif
========
>>>>>>> Stashed changes

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="pb-12">
                <form action="{{ isset($blog) ? route('blog.update', $blog->id) : route('blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @if (isset($blog))
                        @method('PUT')  <!-- Use PUT for update -->
                    @endif
                    <div class="sm:col-span-4 mb-5">
                        <label for="username" class="block text-sm/6 font-medium text-gray-900">Title</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input type="text" name="heading" id="heading" class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" placeholder="janesmith" value="{{ old('heading', $blog->heading ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-span-full mb-5">
                        <label for="content" class="block text-sm/6 font-medium text-gray-900">Content</label>
                        <div class="mt-2">
<<<<<<< Updated upstream
                            <textarea name="content" id="content" rows="3" class="tinymce-editor block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
=======
                            <textarea name="content" id="content" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
>>>>>>> Stashed changes
                                value="{{ old('content', $blog->content ?? '') }}"
                            </textarea>
                        </div>
                    </div>
<<<<<<< Updated upstream
                    <div class="col-span-full mb-5">
                        <label for="category_id" class="block text-sm/6 font-medium text-gray-900">Categories</label>
                        <div class="mt-2 grid grid-cols-1">
                            <select id="category_id" name="category_id"
                                class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="" disabled selected>Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ isset($blog) && $blog->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
=======
>>>>>>> Stashed changes
                    <div class="sm:col-span-4 mb-5">
                        <label for="username" class="block text-sm/6 font-medium text-gray-900">Reading Time</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input type="number" name="reading_time" id="reading_time" class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" placeholder="janesmith" value="{{ old('reading_time', $blog->reading_time ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-span-full mb-5">
                        <label class="block text-sm font-medium">Fearured Image</label>
                        <input type="file" name="featured_image" id="featured_image" accept="image/*" class="w-full p-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        @if(isset($blog) && $blog->featured_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Featured Image" width="100">
                            </div>
                        @endif
                    </div>
                    <div class="col-span-full mb-5">
                        <label class="block text-sm font-medium">Left Image</label>
                        <input type="file" name="left_image" id="left_image" accept="image/*" class="w-full p-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        @if(isset($blog) && $blog->left_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $blog->left_image) }}" alt="Left Image" width="100">
                            </div>
                        @endif
                    </div>
                    <div class="col-span-full mb-5">
                        <label for="right_text" class="block text-sm/6 font-medium text-gray-900">Right Text</label>
                        <div class="mt-2">
<<<<<<< Updated upstream
                            <textarea name="right_text" id="right_text" rows="3" class=" tinymce-editor block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
=======
                            <textarea name="right_text" id="right_text" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
>>>>>>> Stashed changes
                                value="{{ old('right_text', $blog->right_text ?? '') }}
                            </textarea>

                        </div>
                    </div>
                    <div class="col-span-full mb-5">
                        <label for="middle_text" class="block text-sm/6 font-medium text-gray-900">Middle Text</label>
                        <div class="mt-2">
<<<<<<< Updated upstream
                            <textarea name="middle_text" id="middle_text" rows="3" class="tinymce-editor block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
=======
                            <textarea name="middle_text" id="middle_text" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
>>>>>>> Stashed changes
                                value="{{ old('middle_text', $blog->middle_text ?? '') }}"
                            </textarea>
                        </div>
                    </div>
                    <div class="col-span-full mb-5">
                        <label class="block text-sm font-medium">Middle Image</label>
                        <input type="file" name="middle_image" id="middle_image" accept="image/*" class="w-full p-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        @if(isset($blog) && $blog->middle_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $blog->middle_image) }}" alt="Middle Image" width="100">
                            </div>
                        @endif
                    </div>
<<<<<<< Updated upstream
                    <div class="sm:col-span-4 mb-5">
                        <label for="sub_title" class="block text-sm/6 font-medium text-gray-900">Sub Title</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input type="text" name="sub_title" id="sub_title" class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" placeholder="janesmith" value="{{ old('sub_title', $blog->sub_title ?? '') }}">
                            </div>
=======
                    <div class="col-span-full mb-5">
                        <label for="sub_title" class="block text-sm/6 font-medium text-gray-900">Sub Title</label>
                        <div class="mt-2">
                            <textarea name="sub_title" id="sub_title" rows="3"class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                value="{{ old('sub_title', $blog->sub_title ?? '') }}"
                            </textarea>
>>>>>>> Stashed changes
                        </div>
                    </div>
                    <div class="col-span-full mb-5">
                        <label for="sub_content" class="block text-sm/6 font-medium text-gray-900">Sub Content</label>
                        <div class="mt-2">
<<<<<<< Updated upstream
                            <textarea name="sub_content" id="sub_content" rows="3" class="tinymce-editor block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
=======
                            <textarea name="sub_content" id="sub_content" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
>>>>>>> Stashed changes
                                value="{{ old('sub_content', $blog->sub_content ?? '') }}"
                            </textarea>
                        </div>
                    </div>
                    <div class="col-span-full mb-5">
                        <label class="block text-sm font-medium">Sub Image</label>
                        <input type="file" name="sub_image" id="sub_image" accept="image/*" class="w-full p-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        @if(isset($blog) && $blog->sub_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $blog->sub_image) }}" alt="Sub Image" width="100">
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-end gap-x-6">
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            {{ isset($blog) ? 'Update' : 'Add' }} <!-- Dynamically change button text -->
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
<<<<<<< Updated upstream
=======
>>>>>>>> Stashed changes:resources/views/blog/index.blade.php
>>>>>>> Stashed changes
