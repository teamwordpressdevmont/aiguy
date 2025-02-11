<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiTool;
use App\Models\AiToolsCategory;
use Carbon\Carbon;


class AiToolDataController extends Controller
{
    public function index()
    {
        $categories = AiToolsCategory::all(); // Fetch all categories
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
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); 
            $extension = $image->getClientOriginalExtension(); 
            $imageName = 'ai-tools-logo-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $logoPath = $image->storeAs('ai-tools-images', $imageName, 'public');
            $validatedData['logo'] = $logoPath;
        }
        if ($request->hasFile('cover')) {
            $image = $request->file('cover');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); 
            $extension = $image->getClientOriginalExtension(); 
            $imageName = 'ai-tools-cover-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $coverPath = $image->storeAs('ai-tools-images', $imageName, 'public');
            $validatedData['cover'] = $coverPath;
        }

        // Save to Database
        AiTool::create([
            'slug' => $request->slug,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'logo' => $logoPath,
            'cover' => $coverPath,
        ]);


        return redirect()->route('ai-tools.list')->with('success', 'AI Tool submitted successfully!');
    }


    public function list()
    {
        $categories = AiToolsCategory::all();
        $aiTools = AiTool::with('category')->get();
        // dd($aiTools);

        return view('ai-tools.tools-list', compact('categories', 'aiTools'));
    }

    public function edit($id)
    {
        $tool = AiTool::findOrFail($id);
        $categories = AiToolsCategory::all();
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
        // if ($request->hasFile('logo')) {
        //     $logoPath = $request->file('logo')->store('logos', 'public');
        //     $tool->logo = $logoPath;
        // }

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); 
            $extension = $image->getClientOriginalExtension(); 
            $imageName = 'ai-tools-logo-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $logoPath = $image->storeAs('ai-tools-images', $imageName, 'public');
            $validatedData['logo'] = $logoPath;
        }

        if ($request->hasFile('cover')) {
            $image = $request->file('cover');
            $formattedDate = Carbon::now()->format('Y-m-d-His');
            $actualFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); 
            $extension = $image->getClientOriginalExtension(); 
            $imageName = 'ai-tools-cover-' . $actualFileName . '-' . $formattedDate . '.' . $extension;
            $coverPath = $image->storeAs('ai-tools-images', $imageName, 'public');
            $validatedData['cover'] = $coverPath;
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