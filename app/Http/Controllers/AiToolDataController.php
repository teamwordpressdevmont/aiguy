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


    public function list()
    {
        $categories = AIToolsCategory::all();
        $aiTools = AiTool::with('category')->get();
        // dd($aiTools);

        return view('ai-tools.tools-list', compact('categories', 'aiTools'));
    }

    public function edit($id)
    {
        $tool = AiTool::findOrFail($id);
        $categories = AIToolsCategory::all();
        return view('ai-tools.index', compact('tool', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'slug' => 'required|unique:ai_tools,slug,' . $id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:ai_tools_category,id',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $tool = AiTool::findOrFail($id);

        // Upload Images (if new images are provided)
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $tool->logo = $logoPath;
        }

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
            $tool->cover = $coverPath;
        }

        // Update Database
        $tool->slug = $request->slug;
        $tool->name = $request->name;
        $tool->category_id = $request->category_id;
        $tool->save();

        return redirect()->back()->with('success', 'AI Tool updated successfully!');
    }


    public function destroy($id)
    {
        $tool = AiTool::findOrFail($id);

        if (!is_null($tool)) {
            $tool->delete();
        }

        return redirect()->route('ai-tools.index')->with('success', 'Tool deleted successfully.');
    }

    public function view($id)
    {
        $tool = AiTool::findOrFail($id);
        return view('ai-tools.view', compact('tool'));
    }
    

}
