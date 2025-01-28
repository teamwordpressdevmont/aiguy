<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Tools;
use App\Models\CategoriesHasTools;
use App\Models\PlatformsHasTools;
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
                $category_image = date('Y-m-d') . '-' . time() . '-' .  $request->image->getClientOriginalName();
                $request->file('image')->storeAs('categories_image', $category_image, 'public');
            }

            $data = [
                'name'        => $request->name,
                'description' => $request->description,
                'image'       => $category_image,
            ];

            $category = Category::create($data);
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

            $category = Category::findOrFail($request->category_id);

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
                $category_image = date('Y-m-d') . '-' . time() . '-' .  $request->image->getClientOriginalName();
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

            $category = Category::findOrFail($request->category_id);

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
                $platform_image = date('Y-m-d') . '-' . time() . '-' .  $request->image->getClientOriginalName();
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
                $platform_image = date('Y-m-d') . '-' . time() . '-' .  $request->image->getClientOriginalName();
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
                $tool_image = date('Y-m-d') . '-' . time() . '-' .  $request->logo->getClientOriginalName();
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

                    $category = Category::findOrFail( $value );

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
                $tool_image = date('Y-m-d') . '-' . time() . '-' .  $request->logo->getClientOriginalName();
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
                    $category = Category::findOrFail( $value );
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

    public function fetchTool( Request $request )
    {
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
}
