<?php

namespace App\Http\Controllers;

use App\Models\AffiliateLinks;
use App\Models\Categories;
use App\Models\Tools;
use App\Models\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 


class AffiliateLinkController extends Controller
{

    // List Affiliate Link
    public function index(Request $request)

    {
        try {

            if ($request->has('category_id')) {

                $categoryExists = Categories::find($request->input('category_id'));

                if (!$categoryExists) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Category ID does not exist.'
                    ], 404);

                }
            }

            if ($request->has('tool_id')) {

                $toolExists = Tools::find($request->input('tool_id'));

                if (!$toolExists) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Tool ID does not exist.'
                    ], 404);

                }
            }

            if ($request->has('course_id')) {

                $courseExists = Courses::find($request->input('course_id'));

                if (!$courseExists) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Course ID does not exist.'
                    ], 404);

                }
            }


            $query = AffiliateLinks::query();

            if ($request->has('category_id')) {

                $query->where('category_id', $request->input('category_id'));
            }

            if ($request->has('tool_id')) {

                $query->where('tool_id', $request->input('tool_id'));
            }

            if ($request->has('course_id')) {

                $query->where('course_id', $request->input('course_id'));
            }

            $affiliateLinks = $query->get();

            return response()->json([
                'success' => true,
                'data' => $affiliateLinks
            ], 200);

        } 
        
        catch (ValidationException $e) 

        {

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);

        } 

        catch (Exception $e) 

        {

            return response()->json([
                'success' => false,
                'error' => 'Something went wrong.',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    

    // Get Affiliate Link
    public function show($id)

    {
        try 
        
        {

            $link = AffiliateLinks::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $link
            ], 200);

        } 

        catch (ValidationException $e) 
        
        {

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);

        }

        catch (\Exception $e) 
        
        {

            return response()->json([
                'success' => false,
                'error' => 'Something went wrong.',
                'message' => $e->getMessage()
            ], 500);

        }
    }


    // Add Affiliate Link
    public function store(Request $request)

    {
        DB::beginTransaction(); // Start transaction
        
        try 
        
        {
          

            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'url'         => 'required|url',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'tool_id'     => 'nullable|exists:tools,id',
                'course_id'   => 'nullable|exists:courses,id',
            ]);
        
           
            $affiliateLink = AffiliateLinks::create($validated);

            DB::commit(); // Commit transaction
        
            return response()->json([
                'success' => true,
                'message' => 'Affiliate link added successfully',
                'data' => $affiliateLink
            ], 201);

        } 

        catch (ValidationException $e) 
        
        {

            DB::rollback(); // Rollback transaction on validation error

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);

        }

        catch (Exception $e) 
        
        {

            DB::rollback(); // Rollback transaction on general error

            return response()->json([
                'success' => false,
                'error' => 'Something went wrong.',
                'message' => $e->getMessage()
            ], 500);

        }

        
        
    }

    // Update Affiliate Link
    public function update(Request $request, $id)
    
    {

        DB::beginTransaction(); // Start transaction

        try 
        
        {

            $link = AffiliateLinks::findOrFail($id);
        
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'url'   => 'sometimes|url',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'tool_id'     => 'nullable|exists:tools,id',
                'course_id'   => 'nullable|exists:courses,id',
            ]);

        
            $link->update($validated);

            DB::commit(); // Commit transaction

        
            return response()->json([
                'success' => true,
                'message' => 'Affiliate link updated successfully',
                'data' => $link
            ]);

        } 

        catch (ValidationException $e) 

        {

            DB::rollback(); // Rollback transaction on validation error

            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
 
        }

        catch (\Exception $e) 

        {

            DB::rollback(); // Rollback transaction on validation error

            return response()->json([
                'success' => false,
                'error' => 'Something went wrong.',
                'message' => $e->getMessage()
            ], 500);

        }
        
    }


    public function destroy($id)

    {
        DB::beginTransaction(); // Start transaction

        try 

        {

            $link = AffiliateLinks::findOrFail($id);

            $link->delete();

            DB::commit(); // Commit transaction


            return response()->json([
                'success' => true,
                'message' => 'Affiliate links deleted successfully'
            ], 200);

        } 

        catch (Exception $e) 

        {

            DB::rollback(); // Rollback transaction on error

            return response()->json([
                'success' => false,
                'message' => 'Affiliate links not found or could not be deleted',
                'error' => $e->getMessage()
            ], 404);
            
        }
        
    }
    
}