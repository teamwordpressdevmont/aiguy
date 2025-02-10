<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiToolsCategory;
use Carbon\Carbon;


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
            
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            
            // Use the original file name and append the timestamp
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); // Get only the file name without extension
            $extension = $image->getClientOriginalExtension(); // Get the file extension
        
            // Generate the new file name
            $imageName = 'ai-tools-category-icon-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            
            // Save the image in the desired path
            $iconPath = $image->storeAs('ai-tools-category-images', $imageName, 'public');
            
            // Update the validated data with the new path
            $validatedData['icon'] = $iconPath;
        }
        
        AiToolsCategory::create($validatedData);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
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
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); 
            $extension = $image->getClientOriginalExtension();
            $imageName = 'ai-tools-category-icon-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $iconPath = $image->storeAs('ai-tools-category-images', $imageName, 'public');
            $validatedData['icon'] = $iconPath;
        }

        $category->update($validatedData); // Update the category with validated data

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.'); // Redirect with success message
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