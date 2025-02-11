<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\AiToolsCategory;
use App\Models\BlogCategory;
use App\Models\Blog;

class BlogController extends Controller
{
    public function getBlogs( Request $request )
    {
        try {

            $per_page = $request->per_page ?? -1;
            $page_no = $request->page_no ?? 1;
            $sort_by = $request->sort_by ?? 'DESC';

            $total_blogs_count = Blog::count();

            if ($per_page == -1) {
                $blogs = Blog::orderBy('created_at', $sort_by)->get();
                $total_pages = 1;
            } else {
                $offset = ($page_no - 1) * $per_page;

                $blogs = Blog::orderBy('created_at', $sort_by)
                    ->offset($offset)
                    ->limit($per_page)
                    ->get();

                $total_pages = ceil($total_blogs_count / $per_page);
            }

            return response()->json([
                'status'         => 'success',
                'message'        => 'Blogs fetched successfully.',
                'total_blogs'  => $total_blogs_count,
                'total_pages'    => $total_pages,
                'current_page'   => $page_no,
                'per_page'       => $per_page,
                'blogs'       => $blogs,
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

    public function getSingleBlog( Request $request )
    {
        try {

            $request->validate([
                'blog_id' => 'required|exists:blogs,id',
            ]);

            $blog = Blog::where( 'id', $request->blog_id )->first();

            if ( empty( $blog ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid blog deleted or not exists',
                ], 422);
            }

            return response()->json([
                'status'     => 'success',
                'message'    => 'Single Blog fetched successfully.',
                'blog_id'    => $blog->id,
                'blog'       => $blog,
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