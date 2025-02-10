@extends('layouts.app')

@section('content')

    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900"> {{ isset($category) ? 'Update AI Tool Category' : 'Add AI Tool Category' }}</h1>
        </div>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <div class="pb-12">

                <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    @if(isset($category))

                    @method('PUT') 

                    @endif 
                    
                    <!-- Name Field -->
                    <div class="sm:col-span-4 mb-5">
                        <label for="name" class="block text-sm/6 font-medium text-gray-900">Name</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input type="text" name="name" id="name"
                                       class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                                       placeholder="Category Name" value="{{ isset($category) ? $category->name : '' }}"> <!-- Pre-fill if editing -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description Field -->
                    <div class="sm:col-span-4 mb-5">
                        <label for="description" class="block text-sm/6 font-medium text-gray-900">Description</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <textarea name="description" id="description"
                                          class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                                          cols="30" rows="10">{{ isset($category) ? $category->description : '' }}</textarea> <!-- Pre-fill if editing -->
                            </div>
                        </div>
                    </div>

                     <!-- Icon Field -->
                     <div class="col-span-full mb-5">
                        <label class="block text-sm/6 font-medium text-gray-900">Icon</label>
                        <div class="mt-2 grid grid-cols-1">
                            <input type="file" name="icon" accept="image/*" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                            @if(isset($category) && $category->icon)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $category->icon) }}" alt="Current Icon" width="100">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Parent Category Dropdown -->
                    <div class="sm:col-span-4 mb-5">
                        <label for="parent_category_id" class="block text-sm/6 font-medium text-gray-900">Parent Category</label>
                        <div class="mt-2">
                            <select name="parent_category_id" id="parent_category_id"
                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                <option value="">Select Parent Category</option>
                                @foreach($allCategories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}" {{ (isset($category) && $parentCategory->id == $category->parent_category_id) ? 'selected' : '' }}>
                                        {{ $parentCategory->name }}
                                    </option> <!-- Pre-select if editing -->
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-x-6">
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            {{ isset($category) ? 'Update' : 'Add' }} 
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection