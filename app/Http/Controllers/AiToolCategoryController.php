<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiToolsCategory;


class AiToolCategoryController extends Controller
{
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
            $iconPath = $request->file('icon')->store('icons', 'public');
            $validatedData['icon'] = $iconPath;
        }

        AiToolsCategory::create($validatedData);

        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully.');
    }
}