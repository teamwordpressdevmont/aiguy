<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\AiToolsCategory;
use App\Models\AiTool;

class AIToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    /* Fetch Category API
    * Method      : GET
    * URL         : domain.com/api/categories/get
    * return      : If any error returns error message with 422 status code, if success return 200 with categories_data
    */
    public function fetchCategories1( Request $request )
    {
        try {
            
            $categoriesData = AiToolsCategory::orderByRaw('CASE WHEN parent_category_id IS NULL THEN id ELSE parent_category_id END')
                                        ->orderByRaw('parent_category_id IS NOT NULL')
                                        ->orderBy('id')
                                        ->get();

            return response()->json([
                'status'         => 'success',
                'message'        => 'Categories fetched successfully.',
                'categories_data'  => $categoriesData,
            ], 200);

        } catch (ValidationException $e) {

            // Handle validation errors
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $exception) {

            // Handle database errors
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message'  => $exception->errorInfo[2],
            ], 422);
        } catch (\Exception $e) {

            // Handle unexpected exceptions
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message'  => $e->getMessage(),
            ], 500);
        }
    }
    
    public function fetchCategories2( Request $request )
    {
        try {
    
            $just_parent_categories = $request->only_parent ?? false;
            
            if ( $just_parent_categories == true ) {
                
                $categories = AiToolsCategory::where( 'parent_category_id', null )->get();

                return response()->json([
                    'status'         => 'success',
                    'message'        => 'Categories fetched successfully.',
                    'categories_data'  => $categories,
                ], 200);
            } else {
                
                $categories = AiToolsCategory::select('*')
                            ->orderBy('parent_category_id')
                            ->orderBy('id')
                            ->get()
                            ->groupBy('parent_category_id');
                
                $categoriesData = [];
                
                foreach ($categories[null] as $parent) { // Fetch parent categories (parent_category_id = NULL)
                    $categoriesData[] = [
                        'parent' => $parent->name,
                        'children' => $categories[$parent->id] ?? [] // Fetch child categories if they exist
                    ];
                }
                
                return response()->json([
                    'status'         => 'success',
                    'message'        => 'Categories fetched successfully.',
                    'categories_data'  => $categoriesData,
                ], 200);
            }
    
        } catch (ValidationException $e) {

            // Handle validation errors
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $exception) {

            // Handle database errors
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message'  => $exception->errorInfo[2],
            ], 422);
        } catch (\Exception $e) {

            // Handle unexpected exceptions
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message'  => $e->getMessage(),
            ], 500);
        }
    }

    public function getTools(Request $request)
    {
        try{
        
        // Fetch query parameters
        $aitool_filter = $request->input('filter'); // popular_tool, latest_tool, alternative_tool
        $limit = $request->input('limit', 10); // Default 10 rows
        $page_no = $request->page_no ?? 1;
        $orderBy = $request->input('order_by', 'desc'); // Default DESC
        
        $columns = $request->columns ?? false;
        
        // Start query
        $query = AiTool::query();
        
        if ( $columns != false ) {
            $columns = explode(',', str_replace(["'", '"'], '', $columns));
            $columns = array_map('trim', $columns);
            $query->select( $columns );
        }
        
        // Apply conditions based on flags
        if ($aitool_filter) {
            $query->where('aitool_filter', $aitool_filter);
        }
        
        // Apply ordering
        $query->orderBy('id', $orderBy);
        
        $offset = ($page_no - 1) * $limit;
        $query->offset( $offset ); 
        
        // Limit the results
        $tools = $query->limit($limit)->get();
        
        

        // Return JSON response
        return response()->json(['success' => true, 'data' => $tools], 200);
        
        }catch (\Exception $e) {
            
             // Log the error for debugging
        \Log::error('Error fetching AI tools: ' . $e->getMessage());

        // Return a JSON response with an error message
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching tools. Please try again later.'
        ], 500);
        }
    }
    
    public function getSingleTool(Request $request)
    {
        try{
        
            $request->validate([
                'slug' => 'required|exists:ai_tools,slug',
            ]);
            $columns = $request->columns ?? false;
            
            if ( $columns != false ) {
                
                $columns = explode(',', str_replace(["'", '"'], '', $columns));
                $columns = array_map('trim', $columns);
                
                $tool = AiTool::where( 'slug', $request->slug )->select($columns)->first();
            } else {
                $tool = AiTool::where( 'slug', $request->slug )->first();
            }
            
            if ( empty( $tool ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid AITool deleted or not exists',
                ], 422);
            }
        
            // Return JSON response
            return response()->json(['success' => true, 'data' => $tool], 200);
        
        }catch (\Exception $e) {
            
             // Log the error for debugging
        \Log::error('Error fetching AI tools: ' . $e->getMessage());

        // Return a JSON response with an error message
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching tools. Please try again later.'
        ], 500);
        }
    }
}
