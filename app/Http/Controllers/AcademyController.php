<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Academy;

class AcademyController extends Controller
{
    public function getAcademies( Request $request )
    {
        try {
            
            $per_page = $request->per_page ?? -1;
            $page_no = $request->page_no ?? 1;
            $sort_by = $request->sort_by ?? 'DESC';
            
            $academies = Academy::query();
            
            $academies->orderBy( 'created_at', $sort_by );
            
            if ( $request->filter == 'ai_guy' ) {
                $academies->where( 'academy_filter', $request->filter );
            }
            
            $total_academies_count = $academies->count();
            
            if ( $per_page == -1 ) {
                $total_pages = 1;
            } else {
                $offset = ($page_no - 1) * $per_page;
                $academies->offset( $offset ); 
                $academies->limit( $per_page );
                $total_pages = ceil($total_academies_count / $per_page);
            }
            
            $academiesData = $academies->get();

            return response()->json([
                'status'         => 'success',
                'message'        => 'Academies fetched successfully.',
                'total_academies'  => $total_academies_count,
                'total_pages'    => $total_pages,
                'current_page'   => $page_no,
                'per_page'       => $per_page,
                'academies'       => $academiesData,
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
}