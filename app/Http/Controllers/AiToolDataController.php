<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiTool;
use App\Models\AIToolsCategory;

class AiToolDataController extends Controller
{
    public function index()
    {
        $categories = AIToolsCategory::all(); // Fetch all categories
        return view('ai-tools.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:ai_tools,slug',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:ai_tools_category,id',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',

        ]);

        // Upload Images
        $logoPath = $request->file('logo') ? $request->file('logo')->store('logos', 'public') : null;
        $coverPath = $request->file('cover') ? $request->file('cover')->store('covers', 'public') : null;

        // Save to Database
        AiTool::create([
            'slug' => $request->slug,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'logo' => $logoPath,
            'cover' => $coverPath,
        ]);

        return redirect()->back()->with('success', 'AI Tool submitted successfully!');
    }

}
