<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Tools;
use App\Models\Course;
use App\Models\Video;
use App\Models\ToolsInteraction;
use App\Models\CoursesInteraction;
use App\Models\VideoInteraction;
use App\Models\AffiliateInteraction;
use App\Models\ToolComments;
use App\Models\ToolsReviews;

class AdminController extends Controller
{

    public function index()
    {
        //
    }

    /* Add User API
    * Method     : GET
    * URL        : domain.com/api/admin/get-users
    * Parameters : name, status, per_page, page_no, sort_by.
    * name       : not required,
    * status     : not required format ( 0, 1 )
    * per_page   : not required, default -1,
    * page_no    : not required, default 1,
    * sort_by    : not required, default DESC, format( 'ASC', 'DESC' )
    */
    public function getAllUsers( Request $request )
    {
        try {

            $per_page    = $request->per_page ?? -1;
            $page_no     = $request->page_no ?? 1;
            $sort_by     = $request->sort_by ?? 'DESC';

            $query = User::query();

            if (!empty($request->name)) {
                $query->where('name', 'LIKE', "%{$request->name}%");
            }

            if (!empty($request->status)) {
                $query->where('status', '=', $request->status);
            }

            $query->orderBy('created_at', $sort_by);

            // Get total records before pagination
            $total_users = $query->count();

            if ($per_page == -1) {
                $users = $query->get();
                $total_pages = 1;
            } else {
                $users = $query->offset(($page_no - 1) * $per_page)
                               ->limit($per_page)
                               ->get();
                $total_pages = ceil($total_users / $per_page);
            }

            return response()->json([
                'status'        => 'success',
                'message'       => 'Users Fetched successfully.',
                'users'         => $users,
                'total_users'   => $total_users,
                'total_pages'   => $total_pages,
                'current_page'  => $page_no,
                'per_page'      => $per_page,
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

    /* User Approve/disapprove/delete API
    * Method     : GET
    * URL        : domain.com/api/admin/user-action
    * Parameters : user_id, action.
    * user_id    : required,
    * action     : required, format ( approve, disapprove, delete )
    */
    public function userAction( Request $request )
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'user_id'   => 'required|exists:users,id',
                'action'    => 'required',
            ]);

            $message = '';

            $user = User::where( 'id', $request->user_id )->first();

            if ( $request->action == 'approve' ) {

                $message = 'approved';
                $status  = 1;

                $user->update( [
                    'status'    => $status
                ] );

            } elseif( $request->action == 'disapprove' ) {
                $message = 'disapproved';
                $status  = 0;

                $user->update( [
                    'status'    => $status
                ] );

            } elseif ( $request->action == 'delete' ) {
                $message = 'deleted';

                if ( $user->avatar != null ) {
                    Storage::disk('public')->delete('users-avatar/' . $user->avatar);
                }

                $user->delete();
            }
            DB::commit();
            return response()->json([
                'status'        => 'success',
                'message'       => 'User '. $message .'.',
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

    /* Admin Update Settings API
    * Method                : POST
    * URL                   : domain.com/api/admin/update-settings
    * Parameters            : user_id, action, avatar, email, current_password, password, password_confirmation.
    * user_id               : required,
    * action                : required, format ( avatar, email, password )
    * avatar                : not required, if action is avatar then use this paramater
    * email                 : required, if action is email then use this paramaeter.
    * current_password      : required, if action is password then use this paramaeter.
    * password              : required, if action is password then use this paramaeter.
    * password_confirmation : required, if action is password then use this paramaeter.
    */
    public function updateAdminSettings( Request $request )
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'user_id'   => 'required|exists:users,id',
                'action'    => 'required',
            ]);

            $message = '';
            $user = User::where( 'id', $request->user_id )->first();
            if ( $request->action == 'avatar' ) {

                $request->validate([
                    'avatar'   => 'nullable|mimes:jpg,jpeg,png,gif,webp,heic,heif,svg|max:2048',
                ]);

                $message = 'avatar';

                $user_avatar = $user->avatar;

                if ( $user_avatar != null ) {
                    if ( $request->hasfile( 'avatar' ) ) {
                        Storage::disk('public')->delete('users-avatars/' . $user_avatar);
                        $user_avatar = date('Y-m-d') . '-' . time() . '-' .  preg_replace('/[^A-Za-z0-9\-.]/', '_', $request->avatar->getClientOriginalName());
                        $request->file('avatar')->storeAs('users-avatars', $user_avatar, 'public');
                    }
                } else {
                    if ( $request->hasfile( 'avatar' ) ) {
                        $user_avatar = date('Y-m-d') . '-' . time() . '-' .  preg_replace('/[^A-Za-z0-9\-.]/', '_', $request->avatar->getClientOriginalName());
                        $request->file('avatar')->storeAs('users-avatars', $user_avatar, 'public');
                    }
                }

                $user->avatar = $user_avatar;
                $user->save();

            } elseif( $request->action == 'email' ) {
                $message = 'email';

                $request->validate([
                    'email'        => [
                        'required',
                        'email',
                        Rule::unique('users', 'email')->ignore($user->id),
                    ],
                ]);

                $user->update( [
                    'email'    => $request->email,
                ] );

            } elseif ( $request->action == 'password' ) {
                $message = 'password';

                $current_password = $user->password;

                $request->validate( [
                    'current_password'  => 'required',
                    'password'          => ['required', 'confirmed'],
                ]);

                if (!Hash::check($request->current_password, $current_password)) {
                    return response()->json([
                        'status'        => 'error',
                        'message'       => 'Current password does not matched.',
                    ], 422);
                }

                if (Hash::check($request->password, $current_password)) {
                    return response()->json([
                        'status'        => 'error',
                        'message'       => 'The new password cannot be the same as the current password. Choose a different password.',
                    ], 422);
                }

                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
            DB::commit();
            return response()->json([
                'status'        => 'success',
                'message'       => 'User '. $message .' updated successfully.',
                'user_id'       => $user->id,
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

    /* Get Pending Reviews API
    * Method     : GET
    * URL        : domain.com/api/admin/get-pending-reviews
    * Parameters : per_page, page_no, sort_by.
    * per_page   : not required, default -1,
    * page_no    : not required, default 1,
    * sort_by    : not required, default DESC, format( 'ASC', 'DESC' )
    */
    public function getPendingReviews( Request $request )
    {
        try {

            $per_page    = $request->per_page ?? -1;
            $page_no     = $request->page_no ?? 1;
            $sort_by     = $request->sort_by ?? 'DESC';

            $query = ToolsReviews::query();
            $query->where('status', '=', 0);

            $query->orderBy('created_at', $sort_by);

            $total_reviews = $query->count();

            if ($per_page == -1) {
                $reviews = $query->get();
                $total_pages = 1;
            } else {
                $reviews = $query->offset(($page_no - 1) * $per_page)
                               ->limit($per_page)
                               ->get();
                $total_pages = ceil($total_reviews / $per_page);
            }

            return response()->json([
                'status'        => 'success',
                'message'       => 'Pending Reviews Fetched successfully.',
                'reviews'         => $reviews,
                'total_reviews'   => $total_reviews,
                'total_pages'   => $total_pages,
                'current_page'  => $page_no,
                'per_page'      => $per_page,
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

    /* Get Pending Comments API
    * Method     : GET
    * URL        : domain.com/api/admin/get-pending-comments
    * Parameters : per_page, page_no, sort_by.
    * per_page   : not required, default -1,
    * page_no    : not required, default 1,
    * sort_by    : not required, default DESC, format( 'ASC', 'DESC' )
    */
    public function getPendingComments( Request $request )
    {
        try {

            $per_page    = $request->per_page ?? -1;
            $page_no     = $request->page_no ?? 1;
            $sort_by     = $request->sort_by ?? 'DESC';

            $query = ToolComments::query();
            $query->where('status', '=', 0);

            $query->orderBy('created_at', $sort_by);

            $total_comments = $query->count();

            if ($per_page == -1) {
                $comments = $query->get();
                $total_pages = 1;
            } else {
                $comments = $query->offset(($page_no - 1) * $per_page)
                               ->limit($per_page)
                               ->get();
                $total_pages = ceil($total_comments / $per_page);
            }

            return response()->json([
                'status'        => 'success',
                'message'       => 'Pending Comments Fetched successfully.',
                'comments'         => $comments,
                'total_comments'   => $total_comments,
                'total_pages'   => $total_pages,
                'current_page'  => $page_no,
                'per_page'      => $per_page,
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

    /* Admin Moderate Reviews API
    * Method                : GET
    * URL                   : domain.com/api/admin/admin-reviews-actions
    * Parameters            : review_id, action.
    * review_id             : required,
    * action                : required, format ( approve, disapprove )
    */
    public function adminReviewsActions( Request $request )
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'review_id'   => 'required|exists:tools_reviews,id',
                'action'    => 'required'
            ]);

            $message = '';
            $review = ToolsReviews::where( 'id', $request->review_id )->first();
            if ( $request->action == 'approve' ) {

                $message = 'approved';

                $review->status = 1;
                $review->save();

            } elseif( $request->action == 'disapprove' ) {
                $review->delete();
                $message = 'disapproved';
            }

            DB::commit();
            return response()->json([
                'status'        => 'success',
                'message'       => 'Review '. $message .' successfully.',
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

    /* Admin Moderate Comments API
    * Method                : GET
    * URL                   : domain.com/api/admin/admin-comments-actions
    * Parameters            : comment_id, action.
    * comment_i             : required,
    * action                : required, format ( approve, disapprove )
    */
    public function adminCommentsActions( Request $request )
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'comment_id'   => 'required|exists:tool_comments,id',
                'action'    => 'required'
            ]);

            $message = '';
            $comment = ToolComments::where( 'id', $request->comment_id )->first();
            if ( $request->action == 'approve' ) {

                $message = 'approved';

                $comment->status = 1;
                $comment->save();

            } elseif( $request->action == 'disapprove' ) {
                $comment->delete();
                $message = 'disapproved';
            }

            DB::commit();
            return response()->json([
                'status'        => 'success',
                'message'       => 'Comment '. $message .' successfully.',
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

    /* Get Clicks Analytics API
    * Method     : GET
    * URL        : domain.com/api/admin/clicks-analytics
    * Parameters : per_page, page_no, sort_by strategy.
    * per_page   : not required, default -1,
    * page_no    : not required, default 1,
    * sort_by    : not required, default DESC, format( 'ASC', 'DESC' )
    * strategy   : required, format( 'tools, courses, videos, affiliate' )
    */
    public function getClicksAnalytics( Request $request )
    {
        try {

            $request->validate([
                'strategy'  => 'required',
            ]);

            $per_page    = $request->per_page ?? -1;
            $page_no     = $request->page_no ?? 1;
            $sort_by     = $request->sort_by ?? 'DESC';

            switch( $request->strategy ) {
                case 'tools' :
                    $query = Tools::query()
                        ->withCount('ToolsInteraction')
                        ->orderBy('tools_interaction_count', $sort_by);
                    $total_tools = $query->count();

                    if ($per_page == -1) {
                        $tools = $query->get();
                        $total_pages = 1;
                    } else {
                        $tools = $query->offset(($page_no - 1) * $per_page)
                                       ->limit($per_page)
                                       ->get();
                        $total_pages = ceil($total_tools / $per_page);
                    }

                    return response()->json([
                        'status'        => 'success',
                        'message'       => 'Tools fetched successfully.',
                        'tools'         => $tools,
                        'total_tools'   => $total_tools,
                        'total_pages'   => $total_pages,
                        'current_page'  => $page_no,
                        'per_page'      => $per_page,
                    ], 200);
                    break;
                case 'affiliate' :
                    //Done this when affiliate module done.
                    break;
                case 'courses' :
                    $query = Course::query()
                        ->withCount('CoursesInteraction')
                        ->orderBy('courses_interaction_count', $sort_by);
                    $total_courses = $query->count();

                    if ($per_page == -1) {
                        $courses = $query->get();
                        $total_pages = 1;
                    } else {
                        $courses = $query->offset(($page_no - 1) * $per_page)
                                       ->limit($per_page)
                                       ->get();
                        $total_pages = ceil($total_courses / $per_page);
                    }

                    return response()->json([
                        'status'        => 'success',
                        'message'       => 'Tools fetched successfully.',
                        'courses'         => $courses,
                        'total_courses'   => $total_courses,
                        'total_pages'   => $total_pages,
                        'current_page'  => $page_no,
                        'per_page'      => $per_page,
                    ], 200);
                    break;

                case 'videos' :
                    $query = Video::query()
                        ->withCount('VideoInteraction')
                        ->orderBy('video_interaction_count', $sort_by);
                    $total_videos = $query->count();

                    if ($per_page == -1) {
                        $videos = $query->get();
                        $total_pages = 1;
                    } else {
                        $videos = $query->offset(($page_no - 1) * $per_page)
                                       ->limit($per_page)
                                       ->get();
                        $total_pages = ceil($total_videos / $per_page);
                    }

                    return response()->json([
                        'status'        => 'success',
                        'message'       => 'Tools fetched successfully.',
                        'videos'         => $videos,
                        'total_videos'   => $total_videos,
                        'total_pages'   => $total_pages,
                        'current_page'  => $page_no,
                        'per_page'      => $per_page,
                    ], 200);
                    break;
            }

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
