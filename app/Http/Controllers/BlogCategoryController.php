<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Carbon\Carbon;

class BlogCategoryController extends Controller
{
    public function index() {
        $allCategories = BlogCategory::all();
        return view('blog-category.index', compact('allCategories'));
    }


    // Display a list of all categories
    public function showList() {
        $categories = BlogCategory::all();
        return view('blog-category.list', compact('categories'));
    }


    // Display the form for creating a new category
    public function create() {
        // Retrieve all categories to list as potential parents.
        $allCategories = BlogCategory::all();
        return view('blog-category.index', compact('allCategories'));

    }

    // Store a new category in the database
    public function store(Request $request) {
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'slug'              => 'required|unique:blog_category,slug',
            'description'       => 'nullable|string',
            'icon'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_category_id'=> 'nullable|exists:blog_category,id',
        ]);

        if ($request->hasFile('icon')) {
            // If a new icon is uploaded, store it and update the path
            $image = $request->file('icon');

            $formattedDate = Carbon::now()->format('Y-m-d-His');

            // Use the original file name and append the timestamp
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); // Get only the file name without extension
            $extension = $image->getClientOriginalExtension(); // Get the file extension

            // Generate the new file name
            $imageName = 'blog-category-icon-' . $actualFileName . '-' . $formattedDate . '.' . $extension;

            // Save the image in the desired path
            $iconPath = $image->storeAs('blog-category-images', $imageName, 'public');

            // Update the validated data with the new path
            $validatedData['icon'] = $iconPath;
        }

        BlogCategory::create($validatedData);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

     // Display a specific category
    public function show($id) {
     $category = BlogCategory::findOrFail($id); // Retrieve the category by ID
     return view('blog-category.show', compact('category')); // Pass the category to the view
    }


    // Display the form for editing a category
     public function edit($id) {
        $category = BlogCategory::findOrFail($id); // Retrieve the category by ID
        $allCategories = BlogCategory::all(); // Retrieve all categories for the parent dropdown
        return view('blog-category.index', compact('category', 'allCategories')); // Pass both to the view
     }

    // Update an existing category in the database
    public function update(Request $request, $id) {
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'slug'              => 'required|unique:blog_category,slug,' . $id,
            'description'       => 'nullable|string',
            'icon'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_category_id'=> 'nullable|exists:blogger_category,id',
        ]);

        $category = BlogCategory::findOrFail($id); // Retrieve the category by ID

        if ($request->hasFile('icon')) {
            // If a new icon is uploaded, store it and update the path
            $image = $request->file('icon');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $imageName = 'blog-category-icon-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $iconPath = $image->storeAs('blog-category-images', $imageName, 'public');
            $validatedData['icon'] = $iconPath;
        }

        $category->update($validatedData); // Update the category with validated data

        return redirect()->back()->with('success', 'Category updated successfully.'); // Redirect with success message
    }

    // Delete a category from the database
    public function destroy($id) {
        $category = BlogCategory::findOrFail($id); // Retrieve the category by ID

        if ($category) {
            $category->delete(); // Delete the category
        }

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
