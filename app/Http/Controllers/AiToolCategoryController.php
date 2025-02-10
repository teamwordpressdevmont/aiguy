<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiToolsCategory;


class AiToolCategoryController extends Controller
{
    public function index() {
        $allCategories = AiToolsCategory::all();
        return view('ai-tools-category.index', compact('allCategories')); 
    }

    
    // Display a list of all categories
    public function showList()
    {
        $categories = AiToolsCategory::all();
        return view('ai-tools-category.list', compact('categories'));
    }


    // Display the form for creating a new category
    public function create()
    {
        // Retrieve all categories to list as potential parents.
        $allCategories = AiToolsCategory::all();
        return view('ai-tools-category.index', compact('allCategories'));

    }

    // Store a new category in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'icon'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_category_id'=> 'nullable|exists:ai_tools_category,id',
        ]);

        if ($request->hasFile('icon')) {
            // If a new icon is uploaded, store it and update the path
            $image = $request->file('icon');
            
            // Generate a unique file name using model name, image name, and current timestamp
            $imageName = 'ai-tools-category-' . time() . '.' . $image->getClientOriginalExtension();
            
            // Save the image in the desired path
            $iconPath = $image->storeAs('ai-tools-category-images', $imageName, 'public');
            
            // Update the validated data with the new path
            $validatedData['icon'] = $iconPath;
        }

        AiToolsCategory::create($validatedData);

        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully.');
    }

     // Display a specific category
     public function show($id)
    {
         $category = AiToolsCategory::findOrFail($id); // Retrieve the category by ID
         return view('ai-tools-category.show', compact('category')); // Pass the category to the view
    }
 

     // Display the form for editing a category
     public function edit($id)
     {
         $category = AiToolsCategory::findOrFail($id); // Retrieve the category by ID
         $allCategories = AiToolsCategory::all(); // Retrieve all categories for the parent dropdown
         return view('ai-tools-category.index', compact('category', 'allCategories')); // Pass both to the view
     }

      // Update an existing category in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'icon'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'parent_category_id'=> 'nullable|exists:ai_tools_category,id',
        ]);

        $category = AiToolsCategory::findOrFail($id); // Retrieve the category by ID

        if ($request->hasFile('icon')) {
            // If a new icon is uploaded, store it and update the path
            $image = $request->file('icon');
            
            // Generate a unique file name using model name, image name, and current timestamp
            $imageName = 'ai-tools-category-' . time() . '.' . $image->getClientOriginalExtension();
            
            // Save the image in the desired path
            $iconPath = $image->storeAs('ai-tools-category-images', $imageName, 'public');
            
            // Update the validated data with the new path
            $validatedData['icon'] = $iconPath;
        } else {
            // If no new icon is uploaded, keep the existing icon
            $validatedData['icon'] = $category->icon; // Assuming 'icon' is a column in your table
        }

        $category->update($validatedData); // Update the category with validated data

        return redirect()->route('categories.index')
                         ->with('success', 'Category updated successfully.'); // Redirect with success message
    }

      // Delete a category from the database
      public function destroy($id)
      {
          $category = AiToolsCategory::findOrFail($id); // Retrieve the category by ID
  
          if ($category) {
              $category->delete(); // Delete the category
          }
    
          return redirect()->route('categories.create')->with('success', 'Category deleted successfully.');
      }

    

 

}