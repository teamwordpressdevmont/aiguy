<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\ToolCategory;
use App\Models\Platform;
use App\Models\Tools;
use App\Models\CategoriesHasTools;
use App\Models\PlatformsHasTools;
use App\Models\ToolsReviews;
use App\Models\ToolComments;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ToolManagementController extends Controller

{
    public function index()
    {
        //
    }

    /* Add Category API
    * Method      : POST
    * URL         : domain.com/api/categories/add
    * Parameters  : ( name, description, image )
    * name        : required, unique
    * description : not required, max 1000 characters
    * image       : not required, object, mimes:jpg,jpeg,png,gif,webp,heic,heif,svg.
    * return      : If any error returns error message with 422 status code, if success return 200 with category id.
    */
    public function addCategory( Request $request )
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'name'        => 'required|unique:categories,name',
                'description' => 'nullable|max:1000',
                'image'       => 'nullable|mimes:jpg,jpeg,png,gif,webp,heic,heif,svg|max:2048',
            ]);

            $category_image = null;

            if ( $request->hasfile( 'image' ) ) {
                $category_image = date('Y-m-d') . '-' . time() . '-' .  preg_replace('/[^A-Za-z0-9\-.]/', '_', $request->image->getClientOriginalName());
                $request->file('image')->storeAs('categories_image', $category_image, 'public');
            }

            $data = [
                'name'        => $request->name,
                'description' => $request->description,
                'image'       => $category_image,
            ];

            $category = ToolCategory::create($data);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Category added successfully.',
                'category_id'  => $category->id,
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

    /* Edit Category API
    * Method      : POST
    * URL         : domain.com/api/categories/edit
    * Parameters  : ( category_id, name, description, image )
    * category_id : required
    * name        : required, unique
    * description : not required, max 1000 characters
    * image       : not required, object, mimes:jpg,jpeg,png,gif,webp,heic,heif,svg.
    * return      : If any error returns error message with 422 status code, if success return 200 with category id.
    */
    public function editCategory( Request $request )
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'category_id' => 'required|exists:categories,id',
            ]);

            $category = ToolCategory::findOrFail($request->category_id);

            if ( empty( $category ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid category deleted or not exists',
                ], 422);
            }

            $request->validate([
                'name'        => [
                    'required',
                    Rule::unique('categories', 'name')->ignore($request->category_id),
                ],
                'description' => 'nullable|max:1000',
                'image'       => 'nullable|mimes:jpg,jpeg,png,gif,webp,heic,heif,svg|max:2048',
            ]);

            $category_image = $category->image;

            if ( $request->hasfile( 'image' ) ) {
                Storage::disk('public')->delete('categories_image/' . $category_image);
                $category_image = date('Y-m-d') . '-' . time() . '-' .  preg_replace('/[^A-Za-z0-9\-.]/', '_', $request->image->getClientOriginalName());
                $request->file('image')->storeAs('categories_image', $category_image, 'public');
            }

            $data = [
                'name'        => $request->name,
                'description' => $request->description,
                'image'       => $category_image,
            ];

            $category->update($data);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully.',
                'category_id'  => $category->id,
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

    /* Delete Category API
    * Method      : GET
    * URL         : domain.com/api/categories/delete
    * Parameters  : ( category_id )
    * category_id : required
    * return      : If any error returns error message with 422 status code, if success return 200.
    */
    public function deleteCategory( Request $request )
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'category_id' => 'required|exists:categories,id',
            ]);

            $category = ToolCategory::findOrFail($request->category_id);

            if ( empty( $category ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid category deleted or not exists',
                ], 422);
            }

            $category_image = $category->image;

            if ( $category_image != null || $category_image != '' ) {
                Storage::disk('public')->delete('categories_image/' . $category_image);
            }

            $categories_has_tools = CategoriesHasTools::where( 'category_id', $category->id );
            $categories_has_tools->delete();

            $category->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully.',
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

    /* Fetch Category tools API
    * Method      : GET
    * URL         : domain.com/api/categories/get-tools
    * Parameters  : ( category_id, per_page, page_no )
    * category_id : required
    * per_page    : not required, default -1,
    * page_no     : not required, default 1,
    * return      : If any error returns error message with 422 status code, if success return 200 with category, tools, total_page, page_no.
    */
    public function fetchToolsCategory( Request $request )
    {
        try {

            $request->validate([
                'category_id' => 'required|exists:categories,id',
            ]);

            $category = ToolCategory::findOrFail($request->category_id);

            if ( empty( $category ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid category deleted or not exists',
                ], 422);
            }

            $per_page = $request->per_page ?? -1;
            $page_no = $request->page_no ?? 1;

            $query = CategoriesHasTools::where('category_id', $category->id);

            $total_records = $query->count();

            if ($per_page > 0) {
                $offset = ($page_no - 1) * $per_page;
                $query->limit($per_page)->offset($offset);
            }

            $categories_has_tools = $query->get();

            $tools_data = [];

            if ( isset( $categories_has_tools ) && ! empty( $categories_has_tools ) && $categories_has_tools != null && $total_records > 0 ) {
                foreach( $categories_has_tools as $tools ) {
                    $tools_data[] = $tools->tools;
                }
            }

            if ( empty( $tools_data ) ) {
                $tools_data[] = 'No tools found in this category';
            }

            $total_pages = ($per_page > 0) ? ceil($total_records / $per_page) : 1;

            return response()->json([
                'status' => 'success',
                'message' => 'Category tools fetched successfully.',
                'category'  => $category,
                'tools'     => $tools_data,
                'total_pages' => $total_pages,
                'page_no'   => $page_no,
            ], 200);

        } catch (ValidationException $e) {

            // Handle validation errors
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $exception) {

            // Handle database errors
            return response()->json([
                'status'  => 'error',
                'message'  => $exception->errorInfo[2],
            ], 422);
        } catch (\Exception $e) {

            // Handle unexpected exceptions
            return response()->json([
                'status'  => 'error',
                'message'  => $e->getMessage(),
            ], 500);
        }
    }

    /* Fetch Category API
    * Method      : GET
    * URL         : domain.com/api/categories/get
    * Parameters  : ( per_page, page_no, sort_by )
    * per_page    : not required, default -1,
    * page_no     : not required, default 1,
    * return      : If any error returns error message with 422 status code, if success return 200 with total_categories, total_pages, current_page', per_page, categories
    */
    public function fetchCategories( Request $request )
    {
        try {

            $per_page = $request->per_page ?? -1;
            $page_no = $request->page_no ?? 1;
            $sort_by = $request->sort_by ?? 'DESC';

            $total_categories_count = ToolCategory::count();

            if ($per_page == -1) {
                $category = ToolCategory::orderBy('created_at', $sort_by)->get();
                $total_pages = 1;
            } else {
                $offset = ($page_no - 1) * $per_page;

                $category = ToolCategory::orderBy('created_at', $sort_by)
                    ->offset($offset)
                    ->limit($per_page)
                    ->get();

                $total_pages = ceil($total_categories_count / $per_page);
            }

            return response()->json([
                'status'         => 'success',
                'message'        => 'Categories fetched successfully.',
                'total_categories'  => $total_categories_count,
                'total_pages'    => $total_pages,
                'current_page'   => $page_no,
                'per_page'       => $per_page,
                'categories'       => $category,
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

    /* Add Platform API
    * Method      : POST
    * URL         : domain.com/api/platforms/add
    * Parameters  : ( name, description, image )
    * name        : required, unique
    * description : not required, max 1000 characters
    * image       : not required, object, mimes:jpg,jpeg,png,gif,webp,heic,heif,svg.
    * return      : If any error returns error message with 422 status code, if success return 200 with platform id.
    */
    public function addPlatform( Request $request )
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'name'        => 'required|unique:platforms,name',
                'description' => 'nullable|max:1000',
                'image'       => 'nullable|mimes:jpg,jpeg,png,gif,webp,heic,heif,svg|max:2048',
            ]);

            $platform_image = null;

            if ( $request->hasfile( 'image' ) ) {
                $platform_image = date('Y-m-d') . '-' . time() . '-' .  preg_replace('/[^A-Za-z0-9\-.]/', '_', $request->image->getClientOriginalName());
                $request->file('image')->storeAs('platforms_image', $platform_image, 'public');
            }

            $data = [
                'name'        => $request->name,
                'description' => $request->description,
                'image'       => $platform_image,
            ];

            $platform = Platform::create($data);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Platform added successfully.',
                'platform_id'  => $platform->id,
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

    /* Edit Platform API
    * Method      : POST
    * URL         : domain.com/api/platforms/edit
    * Parameters  : ( platform_id, name, description, image )
    * platform_id : required
    * name        : required, unique
    * description : not required, max 1000 characters
    * image       : not required, object, mimes:jpg,jpeg,png,gif,webp,heic,heif,svg.
    * return      : If any error returns error message with 422 status code, if success return 200 with platform id.
    */
    public function editPlatform( Request $request )
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'platform_id' => 'required|exists:platforms,id',
            ]);

            $platform = Platform::findOrFail($request->platform_id);

            if ( empty( $platform ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid platform deleted or not exists',
                ], 422);
            }

            $request->validate([
                'name'        => [
                    'required',
                    Rule::unique('platforms', 'name')->ignore($request->platform_id),
                ],
                'description' => 'nullable|max:1000',
                'image'       => 'nullable|mimes:jpg,jpeg,png,gif,webp,heic,heif,svg|max:2048',
            ]);

            $platform_image = $platform->image;

            if ( $request->hasfile( 'image' ) ) {
                Storage::disk('public')->delete('platforms_image/' . $platform_image);
                $platform_image = date('Y-m-d') . '-' . time() . '-' .  preg_replace('/[^A-Za-z0-9\-.]/', '_', $request->image->getClientOriginalName());
                $request->file('image')->storeAs('platforms_image', $platform_image, 'public');
            }

            $data = [
                'name'        => $request->name,
                'description' => $request->description,
                'image'       => $platform_image,
            ];

            $platform->update($data);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Platform updated successfully.',
                'platform_id'  => $platform->id,
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

    /* Delete Platform API
    * Method      : GET
    * URL         : domain.com/api/platforms/delete
    * Parameters  : ( platform_id )
    * platform_id : required
    * return      : If any error returns error message with 422 status code, if success return 200.
    */
    public function deletePlatform( Request $request )
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'platform_id' => 'required|exists:platforms,id',
            ]);

            $platform = Platform::findOrFail($request->platform_id);

            if ( empty( $platform ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid platform deleted or not exists',
                ], 422);
            }

            $platform_image = $platform->image;

            if ( $platform_image != null || $platform_image != '' ) {
                Storage::disk('public')->delete('platforms_image/' . $platform_image);
            }

            $platforms_has_tools = PlatformsHasTools::where( 'platform_id', $platform->id );
            $platforms_has_tools->delete();

            $platform->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Platform deleted successfully.',
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

    /* Fetch Platform tools API
    * Method      : GET
    * URL         : domain.com/api/platforms/get-tools
    * Parameters  : ( platform_id, per_page, page_no )
    * platform_id : required
    * per_page    : not required, default -1,
    * page_no     : not required, default 1,
    * return      : If any error returns error message with 422 status code, if success return 200 with platform, tools, total_page, page_no.
    */
    public function fetchToolsPlatform( Request $request )
    {
        try {

            $request->validate([
                'platform_id' => 'required|exists:platforms,id',
            ]);

            $platform = Platform::findOrFail($request->platform_id);

            if ( empty( $platform ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid platform deleted or not exists',
                ], 422);
            }

            $per_page = $request->per_page ?? -1;
            $page_no = $request->page_no ?? 1;

            $query = PlatformsHasTools::where('platform_id', $platform->id);

            $total_records = $query->count();

            if ($per_page > 0) {
                $offset = ($page_no - 1) * $per_page;
                $query->limit($per_page)->offset($offset);
            }

            $platform_has_tools = $query->get();

            $tools_data = [];

            if ( isset( $platform_has_tools ) && ! empty( $platform_has_tools ) && $platform_has_tools != null && $total_records > 0 ) {
                foreach( $platform_has_tools as $tools ) {
                    $tools_data[] = $tools->tools;
                }
            }

            if ( empty( $tools_data ) ) {
                $tools_data[] = 'No tools found in this platform';
            }

            $total_pages = ($per_page > 0) ? ceil($total_records / $per_page) : 1;

            return response()->json([
                'status' => 'success',
                'message' => 'Platform tools fetched successfully.',
                'platform'  => $platform,
                'tools'     => $tools_data,
                'total_pages' => $total_pages,
                'page_no'   => $page_no,
            ], 200);

        } catch (ValidationException $e) {

            // Handle validation errors
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $exception) {

            // Handle database errors
            return response()->json([
                'status'  => 'error',
                'message'  => $exception->errorInfo[2],
            ], 422);
        } catch (\Exception $e) {

            // Handle unexpected exceptions
            return response()->json([
                'status'  => 'error',
                'message'  => $e->getMessage(),
            ], 500);
        }
    }

    /* Fetch Platform API
    * Method      : GET
    * URL         : domain.com/api/platforms/get
    * Parameters  : ( per_page, page_no, sort_by )
    * per_page    : not required, default -1,
    * page_no     : not required, default 1,
    * return      : If any error returns error message with 422 status code, if success return 200 with total_platforms, total_pages, current_page', per_page, platforms
    */
    public function fetchPlatforms( Request $request )
    {
        try {

            $per_page = $request->per_page ?? -1;
            $page_no = $request->page_no ?? 1;
            $sort_by = $request->sort_by ?? 'DESC';

            $total_platforms_count = Platform::count();

            if ($per_page == -1) {
                $platforms = Platform::orderBy('created_at', $sort_by)->get();
                $total_pages = 1;
            } else {
                $offset = ($page_no - 1) * $per_page;

                $platforms = Platform::orderBy('created_at', $sort_by)
                    ->offset($offset)
                    ->limit($per_page)
                    ->get();

                $total_pages = ceil($total_platforms_count / $per_page);
            }

            return response()->json([
                'status'            => 'success',
                'message'           => 'Platforms fetched successfully.',
                'total_platforms'   => $total_platforms_count,
                'total_pages'       => $total_pages,
                'current_page'      => $page_no,
                'per_page'          => $per_page,
                'platforms'         => $platforms,
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

    /* Add Tools API
    * Method      : POST
    * URL         : domain.com/api/tools/add
    * Parameters  : ( name, description, logo, price, link, category - array, platform - array )
    * name        : required, unique
    * description : not required, max 1000 characters
    * logo        : not required, object, mimes:jpg,jpeg,png,gif,webp,heic,heif,svg.
    * price       : not required, integer
    * link        : not required
    * category    : category id in array format.
    * platform    : platform id in array format.
    * return      : If any error returns error message with 422 status code, if success return 200 with tool id.
    */
    public function addTool( Request $request )
    {

        DB::beginTransaction();

        try {

            $request->validate([
                'name'        => 'required|unique:platforms,name',
                'description' => 'nullable|max:1000',
                'logo'        => 'nullable|mimes:jpg,jpeg,png,gif,webp,heic,heif,svg|max:2048',
                'price'       => 'nullable',
                'category'    => 'nullable',
                'platform'    => 'nullable',
                'link'        => 'nullable',
            ]);

            $tool_image = null;

            if ( $request->hasfile( 'logo' ) ) {
                $tool_image = date('Y-m-d') . '-' . time() . '-' .  preg_replace('/[^A-Za-z0-9\-.]/', '_', $request->logo->getClientOriginalName());
                $request->file('logo')->storeAs('tools_image', $tool_image, 'public');
            }

            $data = [
                'name'        => $request->name,
                'description' => $request->description,
                'logo'        => $tool_image,
                'price'       => $request->price,
                'link'        => $request->link,
            ];

            $tools = Tools::create($data);

            if( is_array( $request->category ) && isset( $request->category ) ) {
                foreach( $request->category as $key => $value ) {

                    $category = ToolCategory::findOrFail( $value );

                    if ( $category ) {
                        CategoriesHasTools::create([
                            'category_id'   => $category->id,
                            'tool_id'       => $tools->id,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'One of selected category is not exists or deleted.',
                        ], 422);
                    }
                }
            }


            if( is_array( $request->platform ) && isset( $request->platform ) ) {
                foreach( $request->platform as $key => $value ) {

                    $platform = Platform::findOrFail( $value );

                    if ( $platform ) {
                        PlatformsHasTools::create([
                            'platform_id'   => $platform->id,
                            'tool_id'       => $tools->id,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'One of selected platform is not exists or deleted.',
                        ], 422);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tools added successfully.',
                'tool_id'  => $tools->id,
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

    /* Edit Tools API
    * Method      : POST
    * URL         : domain.com/api/tools/edit
    * Parameters  : ( tool_id, name, description, logo, price, link, category - array, platform - array )
    * tool_id     : required
    * name        : required, unique
    * description : not required, max 1000 characters
    * logo        : not required, object, mimes:jpg,jpeg,png,gif,webp,heic,heif,svg.
    * price       : not required, integer
    * link        : not required
    * category    : category id in array format.
    * platform    : platform id in array format.
    * return      : If any error returns error message with 422 status code, if success return 200 with tool id.
    */
    public function editTool( Request $request )
    {

        DB::beginTransaction();

        try {

            $request->validate([
                'tool_id' => 'required|exists:tools,id',
            ]);

            $tool = Tools::findOrFail($request->tool_id);

            if ( empty( $tool ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid tool deleted or not exists',
                ], 422);
            }

            $request->validate([
                'name'        => 'required|unique:platforms,name',
                'description' => 'nullable|max:1000',
                'logo'        => 'nullable|mimes:jpg,jpeg,png,gif,webp,heic,heif,svg|max:2048',
                'price'       => 'nullable',
                'category'    => 'nullable',
                'platform'    => 'nullable',
                'link'        => 'nullable',
            ]);



            $tool_image = $tool->logo;

            if ( $request->hasfile( 'logo' ) ) {
                Storage::disk('public')->delete('tools_image/' . $tool_image);
                $tool_image = date('Y-m-d') . '-' . time() . '-' .  preg_replace('/[^A-Za-z0-9\-.]/', '_', $request->logo->getClientOriginalName());
                $request->file('logo')->storeAs('tools_image', $tool_image, 'public');
            }

            $data = [
                'name'        => $request->name,
                'description' => $request->description,
                'logo'        => $tool_image,
                'price'       => $request->price,
                'link'        => $request->link,
            ];

            $tool->update($data);

            if( is_array( $request->category ) && isset( $request->category ) ) {
                $categories_has_tools = CategoriesHasTools::where( 'tool_id', $tool->id );
                $categories_has_tools->delete();
                foreach( $request->category as $key => $value ) {
                    $category = ToolCategory::findOrFail( $value );
                    if ( $category ) {
                        CategoriesHasTools::create([
                            'category_id'   => $category->id,
                            'tool_id'       => $tool->id,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'One of selected category is not exists or deleted.',
                        ], 422);
                    }
                }
            }


            if( is_array( $request->platform ) && isset( $request->platform ) ) {
                $platforms_has_tools = PlatformsHasTools::where( 'tool_id', $tool->id );
                $platforms_has_tools->delete();
                foreach( $request->platform as $key => $value ) {
                    $platform = Platform::findOrFail( $value );

                    if ( $platform ) {
                        PlatformsHasTools::create([
                            'platform_id'   => $platform->id,
                            'tool_id'       => $tool->id,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'One of selected platform is not exists or deleted.',
                        ], 422);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tools updated successfully.',
                'tool_id'  => $tool->id,
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

    /* Fetch Tools with filters API
    * Method      : GET
    * URL         : domain.com/api/tools/get-tools
    * Parameters  : ( per_page, page_no, sort_by, sort_order, name, category, platform, price_range, ratings, reviews )
    * per_page    : not required, default -1,
    * page_no     : not required, default 1,
    * sort_by     : not required, default created_at, format( 'price', 'avg_rating', 'total_reviews', 'created_at' )
    * sort_order  : not required, default DESC, format( 'ASC', 'DESC' )
    * category    : not required, category id.
    * platform    : not required, platform id.
    * price_range : not required, format ( min-max ), example ( 10-40 )
    * ratings     : not required, format ( 3.0 )
    * reviews     : not required, format ( 16 )
    * return      : If any error returns error message with 422 status code, if success return 200.
    */
    public function fetchTool(Request $request)
    {
        $per_page    = $request->per_page ?? -1;  // Default per_page to show all
        $page_no     = $request->page_no ?? 1;   // Default page number to 1
        $sort_by     = $request->sort_by ?? 'created_at'; // Default sort by created_at
        $sort_order  = $request->sort_order ?? 'DESC';    // Default sorting order

        $query = Tools::query();

        // Filter by name (partial match)
        if (!empty($request->name)) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        }

        // Filter by category
        if (!empty($request->category)) {
            $query->whereHas('CategoriesHasTools', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        // Filter by platform
        if (!empty($request->platform)) {
            $query->whereHas('PlatformsHasTools', function ($q) use ($request) {
                $q->where('platform_id', $request->platform);
            });
        }

        // Filter by price range (expects min_price & max_price in request)
        if (!empty($request->price_range)) {
            $priceRange = explode('-', $request->price_range);
            if (count($priceRange) == 2) {
                $query->whereBetween('price', [$priceRange[0], $priceRange[1]]);
            }
        }

        // Filter by minimum ratings
        if (!empty($request->ratings)) {
            $query->where('avg_rating', '>=', $request->ratings);
        }

        // Filter by minimum reviews count
        if (!empty($request->reviews)) {
            $query->where('total_reviews', '>=', $request->reviews);
        }

        // Sorting by selected criteria (price, ratings, reviews, or created_at)
        if (in_array($sort_by, ['price', 'avg_rating', 'total_reviews', 'created_at'])) {
            $query->orderBy($sort_by, $sort_order);
        }

        // Get total records before pagination
        $total_tools = $query->count();

        // Check if we need to paginate
        if ($per_page == -1) {
            $tools = $query->get(); // Fetch all tools without pagination
            $total_pages = 1;
        } else {
            $tools = $query->offset(($page_no - 1) * $per_page)
                           ->limit($per_page)
                           ->get();
            $total_pages = ceil($total_tools / $per_page);
        }

        // Return response
        return response()->json([
            'status'       => 'success',
            'total_tools'  => $total_tools,
            'total_pages'  => $total_pages,
            'current_page' => $page_no,
            'per_page'     => $per_page,
            'tools'        => $tools,
        ]);
    }

    /* Delete Tools API
    * Method      : GET
    * URL         : domain.com/api/tools/delete
    * Parameters  : ( tool_id )
    * tool_id     : required
    * return      : If any error returns error message with 422 status code, if success return 200.
    */
    public function deleteTool( Request $request )
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'tool_id' => 'required|exists:tools,id',
            ]);

            $tool = Tools::findOrFail($request->tool_id);

            if ( empty( $tool ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid tool deleted or not exists',
                ], 422);
            }

            $tool_image = $tool->logo;

            if ( $tool_image != null || $tool_image != '' ) {
                Storage::disk('public')->delete('platforms_image/' . $tool_image);
            }

            $categories_has_tools = CategoriesHasTools::where( 'tool_id', $tool->id );
            $categories_has_tools->delete();

            $platforms_has_tools = PlatformsHasTools::where( 'tool_id', $tool->id );
            $platforms_has_tools->delete();

            $tool->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tool deleted successfully.',
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

    /* Add Reviews API
    * Method      : POST
    * URL         : domain.com/api/reviews/add
    * Parameters  : ( tool_id, user_id, review, ratings )
    * tool_id     : required
    * user_id     : required
    * review      : required, string, and max 1000 words
    * ratings     : required, minimum 1 and maximum 5
    * return      : If any error returns error message with 422 status code, if success return 200 with tool id and review id.
    */
    public function addReview( Request $request )
    {
       DB::beginTransaction();

        try {

            $request->validate([
                'tool_id' => 'required|exists:tools,id',
                // 'user_id' => 'required',
                'user_id' => 'required|exists:users,id',
            ]);

            $tool = Tools::findOrFail($request->tool_id);

            if ( empty( $tool ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid tool deleted or not exists',
                ], 422);
            }

            $request->validate([
                'review'  => 'required|string|max:1000',
                'ratings' => 'required|string|between:1,5',
            ]);

            $data = [
                'tool_id'   => $tool->id,
                'user_id'   => $request->user_id,
                'review'    => $request->review,
                'ratings'   => (float)$request->ratings,
            ];

            $review = ToolsReviews::create( $data );

            $total_reviews = ToolsReviews::where('tool_id', $tool->id)->count();
            $avg_rating = ToolsReviews::where('tool_id', $tool->id)->avg('ratings');

            $tool->update([
                'total_reviews' => $total_reviews,
                'avg_rating'    => $avg_rating,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Review added successfully.',
                'tool_id'  => $tool->id,
                'review_id'  => $review->id,
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

    /* Fetch tool Review API
    * Method      : GET
    * URL         : domain.com/api/reviews/get
    * Parameters  : ( tool_id, per_page, page_no, sort_by )
    * tool_id     : required
    * per_page    : not required, default -1,
    * page_no     : not required, default 1,
    * sort_by     : not required, default DESC, format( 'ASC', 'DESC' )
    * return      : If any error returns error message with 422 status code, if success return 200 with total_reviews, tool, total_page, current_page, per_page, reviews.
    */
    public function getReview( Request $request )
    {
        try {

            $request->validate([
                'tool_id' => 'required|exists:tools,id',
            ]);

            $tool = Tools::findOrFail($request->tool_id);

            if ( empty( $tool ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid tool deleted or not exists',
                ], 422);
            }

            $per_page = $request->per_page ?? -1;
            $page_no = $request->page_no ?? 1;
            $sort_by = $request->sort_by ?? 'DESC';

            $total_reviews_count = ToolsReviews::where('tool_id', $tool->id)->count();

            if ($per_page == -1) {
                $reviews = ToolsReviews::where('tool_id', $tool->id)
                    ->orderBy('created_at', $sort_by)
                    ->get();
                $total_pages = 1;
            } else {
                $offset = ($page_no - 1) * $per_page;

                $reviews = ToolsReviews::where('tool_id', $tool->id)
                    ->orderBy('created_at', $sort_by)
                    ->offset($offset)
                    ->limit($per_page)
                    ->get();

                $total_pages = ceil($total_reviews_count / $per_page);
            }

            return response()->json([
                'status'         => 'success',
                'message'        => 'Review fetched successfully.',
                'total_reviews'  => $total_reviews_count,
                'total_pages'    => $total_pages,
                'current_page'   => $page_no,
                'per_page'       => $per_page,
                'reviews'        => $reviews,
                'tool'           => $tool,
            ], 200);

        } catch (ValidationException $e) {

            // Handle validation errors
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $exception) {

            // Handle database errors
            return response()->json([
                'status'  => 'error',
                'message'  => $exception->errorInfo[2],
            ], 422);
        } catch (\Exception $e) {

            // Handle unexpected exceptions
            return response()->json([
                'status'  => 'error',
                'message'  => $e->getMessage(),
            ], 500);
        }
    }

    /* Add tool Comment API
    * Method      : POST
    * URL         : domain.com/api/comments/add
    * Parameters  : ( tool_id, user_id, comment, parent_comment_id )
    * tool_id     : required
    * user_id     : required
    * comment     : required, max 1000 words
    * parent_comment_id     : not required
    * return      : If any error returns error message with 422 status code, if success return 200 with tool_id, user_id, comment_id, parent_comment_id.
    */
    public function addComments( Request $request )
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'tool_id' => 'required|exists:tools,id',
                'user_id' => 'required|exists:users,id',
                // 'user_id' => 'required',
            ]);

            $tool = Tools::findOrFail($request->tool_id);

            if ( empty( $tool ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid tool deleted or not exists',
                ], 422);
            }

            $request->validate([
                'parent_comment_id' => 'exists:tool_comments,id',
                'comment' => 'required|max:1000',
            ]);

            $data = [
                'tool_id'   => $tool->id,
                'user_id'   => $request->user_id,
                'comment'   => $request->comment,
            ];

            if ( $request->parent_comment_id ) {
                $data['parent_comment_id'] = $request->parent_comment_id;
            }

            $comment = ToolComments::create( $data );

            DB::commit();
            return response()->json([
                'status'            => 'success',
                'message'           => 'Comment add successfully.',
                'tool_id'           => $tool->id,
                'user_id'           => $request->user_id,
                'comment_id'        => $comment->id,
                'parent_comment_id' => $request->parent_comment_id,
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

    /* Fetch tool Comments API
    * Method      : GET
    * URL         : domain.com/api/comments/get
    * Parameters  : ( tool_id, per_page, page_no, sort_by )
    * tool_id     : required
    * per_page    : not required, default -1,
    * page_no     : not required, default 1,
    * sort_by     : not required, default DESC, format( 'ASC', 'DESC' )
    * return      : If any error returns error message with 422 status code, if success return 200 with tool, comments, total_page, current_page, per_page, reviews.
    */
    public function fetchAllComments( Request $request )
    {
        try {

            $request->validate([
                'tool_id' => 'required|exists:tools,id',
            ]);

            $tool = Tools::findOrFail($request->tool_id);

            if ( empty( $tool ) ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid tool deleted or not exists',
                ], 422);
            }

            $per_page = $request->per_page ?? -1;
            $page_no = $request->page_no ?? 1;
            $sort_by = $request->sort_by ?? 'DESC';

            $total_comments_count = ToolComments::where('tool_id', $tool->id)->count();

            if ($per_page == -1) {
                $comments = ToolComments::where('tool_id', $tool->id)
                    ->orderBy('created_at', $sort_by)
                    ->get();
                $total_pages = 1;
            } else {
                $offset = ($page_no - 1) * $per_page;

                $comments = ToolComments::where('tool_id', $tool->id)
                    ->orderBy('created_at', $sort_by)
                    ->offset($offset)
                    ->limit($per_page)
                    ->get();

                $total_pages = ceil($total_comments_count / $per_page);
            }

            return response()->json([
                'status'         => 'success',
                'message'        => 'Comments fetched successfully.',
                'total_comments'  => $total_comments_count,
                'total_pages'    => $total_pages,
                'current_page'   => $page_no,
                'per_page'       => $per_page,
                'comments'        => $comments,
                'tool'           => $tool,
            ], 200);

        } catch (ValidationException $e) {

            // Handle validation errors
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\QueryException $exception) {

            // Handle database errors
            return response()->json([
                'status'  => 'error',
                'message'  => $exception->errorInfo[2],
            ], 422);
        } catch (\Exception $e) {

            // Handle unexpected exceptions
            return response()->json([
                'status'  => 'error',
                'message'  => $e->getMessage(),
            ], 500);
        }
    }
}
