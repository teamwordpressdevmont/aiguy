@extends('layouts.app')

@section('content')

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <div class="pb-12">

                <form action="{{ isset($tool) ? route('tools.update', $tool->id) : route('tools.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @if (isset($tool))
                        @method('PUT')  <!-- Use PUT for update -->
                    @endif
                    
                    <div class="sm:col-span-4 mb-5">
                        <label for="slug" class="block text-sm/6 font-medium text-gray-900">Slug</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input type="text" name="slug" id="slug"
                                    class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                                    placeholder="chatgpt-plugins" value="{{ old('slug', $tool->slug ?? '') }}">
                            </div>
                        </div>
                    </div>
                
                    <div class="sm:col-span-4 mb-5">
                        <label for="name" class="block text-sm/6 font-medium text-gray-900">Name</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input type="text" name="name" id="name"
                                    class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                                    placeholder="ChatGPT Plugins" value="{{ old('name', $tool->name ?? '') }}">
                            </div>
                        </div>
                    </div>
                
                    <div class="col-span-full mb-5">
                        <label for="category_id" class="block text-sm/6 font-medium text-gray-900">Categories</label>
                        <div class="mt-2 grid grid-cols-1">
                            <select id="category_id" name="category_id" 
                                class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="" disabled selected>Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ isset($tool) && $tool->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                
                    <div class="col-span-full mb-5">
                        <label class="block text-sm/6 font-medium text-gray-900">Logo</label>
                        <div class="mt-2 grid grid-cols-1">
                            <input type="file" name="logo" accept="image/*" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            @if(isset($tool) && $tool->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $tool->logo) }}" alt="Current Logo" width="100">
                                </div>
                            @endif
                        </div>
                    </div>
                
                    <div class="col-span-full mb-5">
                        <label class="block text-sm/6 font-medium text-gray-900">Cover Image</label>
                        <div class="mt-2 grid grid-cols-1">
                            <input type="file" name="cover" accept="image/*" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            @if(isset($tool) && $tool->cover)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $tool->cover) }}" alt="Current Cover" width="100">
                                </div>
                            @endif
                        </div>
                    </div>
                
                    <div class="flex items-center justify-end gap-x-6">
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            {{ isset($tool) ? 'Update' : 'Add' }} <!-- Dynamically change button text -->
                        </button>
                    </div>
                </form>
                
                
            </div>

        </div>
    </main>

@endsection