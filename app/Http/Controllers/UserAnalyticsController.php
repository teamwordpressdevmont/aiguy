<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Tools;
use App\Models\ToolsInteraction;
use App\Models\CoursesInteraction;
use App\Models\VideoInteraction;
use App\Models\AffiliateInteraction;
use App\Models\ToolComments;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserAnalyticsController extends Controller

{
    public function index()
    {
        //
    }

    /* Add Tool Interaction API
    * Method      : POST
    * URL         : domain.com/api/analytics/tool-interaction
    * Parameters  : ( tool_id, user_id )
    * tool_id     : required
    * user_id     : required
    * return      : If any error returns error message with 422 status code, if success return 200 with interaction id.
    */
    public function addToolInteraction( Request $request )
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'tool_id'   => 'required|exists:tools,id',
                // 'user_id'   => 'required|exists:users,id', uncomment when user module active.
                'user_id'   => 'required',
            ]);

            $previous_record = ToolsInteraction::where( 'tool_id', $request->tool_id )->where( 'user_id', $request->user_id )->first();
            if ( $previous_record ) {
                $previous_record->delete();
            }

            $data = [
                'tool_id'   => $request->tool_id,
                'user_id'   => $request->user_id,
            ];

            $new_record = ToolsInteraction::create( $data );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Tool interaction successfully.',
                'tool_interaction_id'  => $new_record->id,
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

    /* Add Course Interaction API
    * Method      : POST
    * URL         : domain.com/api/analytics/course-interaction
    * Parameters  : ( course_id, user_id )
    * course_id   : required
    * user_id     : required
    * return      : If any error returns error message with 422 status code, if success return 200 with interaction id.
    */
    public function addCourseInteraction( Request $request )
    {
        DB::beginTransaction();
        try {

            $request->validate([
                // 'course_id'   => 'required|exists:course,id', uncomment when user module active.
                // 'user_id'   => 'required|exists:users,id', uncomment when user module active.
                'course_id'   => 'required',
                'user_id'   => 'required',
            ]);

            $previous_record = CoursesInteraction::where( 'course_id', $request->course_id )->where( 'user_id', $request->user_id )->first();
            if ( $previous_record ) {
                $previous_record->delete();
            }

            $data = [
                'course_id'   => $request->course_id,
                'user_id'   => $request->user_id,
            ];

            $new_record = CoursesInteraction::create( $data );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Course interaction successfully.',
                'course_interaction_id'  => $new_record->id,
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

    /* Add Video Interaction API
    * Method      : POST
    * URL         : domain.com/api/analytics/video-interaction
    * Parameters  : ( video_id, user_id )
    * video_id    : required
    * user_id     : required
    * return      : If any error returns error message with 422 status code, if success return 200 with interaction id.
    */
    public function addVideoInteraction( Request $request )
    {
        DB::beginTransaction();
        try {

            $request->validate([
                // 'video_id'   => 'required|exists:videos,id', uncomment when user module active.
                // 'user_id'   => 'required|exists:users,id', uncomment when user module active.
                'video_id'   => 'required',
                'user_id'   => 'required',
            ]);

            $previous_record = VideoInteraction::where( 'video_id', $request->video_id )->where( 'user_id', $request->user_id )->first();
            if ( $previous_record ) {
                $previous_record->delete();
            }

            $data = [
                'video_id'   => $request->video_id,
                'user_id'   => $request->user_id,
            ];

            $new_record = VideoInteraction::create( $data );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Video interaction successfully.',
                'video_interaction_id'  => $new_record->id,
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

    /* Add Affiliate Interaction API
    * Method      : POST
    * URL         : domain.com/api/analytics/affiliate-interaction
    * Parameters  : ( user_id, affiliate_id )
    * user_id     : required
    * affiliate_id: required
    * return      : If any error returns error message with 422 status code, if success return 200 with interaction id.
    */
    public function addAffiliateInteraction( Request $request )
    {
        DB::beginTransaction();
        try {

            $request->validate([
                // 'user_id'   => 'required|exists:users,id', uncomment when user module active.
                // 'affiliate_id'   => 'required|exists:affiliates,id', uncomment when user module active.
                'user_id'   => 'required',
                'affiliate_id'   => 'required',
            ]);

            $previous_record = AffiliateInteraction::where( 'affiliate_id', $request->affiliate_id )->where( 'user_id', $request->user_id )->first();
            if ( $previous_record ) {
                $previous_record->delete();
            }

            $data = [
                'user_id'   => $request->user_id,
                'affiliate_id'   => $request->affiliate_id,
            ];

            $new_record = AffiliateInteraction::create( $data );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Affiliate link interaction successfully.',
                'affiliate_link_interaction_id'  => $new_record->id,
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

    /* Fetch Recent Interactions Data API
    * Method      : POST
    * URL         : domain.com/api/analytics/recent-interactions
    * Parameters  : ( user_id )
    * user_id     : required
    * return      : If any error returns error message with 422 status code, if success return 200 with tools, courses, videos.
    */
    public function getRecentInteractionsData ( Request $request )
    {

        try {

            $request->validate([
                // 'user_id'   => 'required|exists:users,id', uncomment when user module active.
                'user_id'   => 'required',
            ]);

            $tool_interactions = ToolsInteraction::where( 'user_id', $request->user_id )->get();

            $tool_data = [];

            if ( $tool_interactions ) {
                foreach ($tool_interactions as $tools) {
                    $tool_data[] = $tools->tool;
                }
            }

            if ( empty( $tools_data ) ) {
                $tool_data[] = 'No Recent interaction with tools found.';
            }

            //Add Logics for courses and videos as well when there modules has been added

            return response()->json([
                'status' => 'success',
                'message' => 'Interaction data fetched successfully.',
                'tools'  => $tool_data,
                'courses'   => '',
                'videos'    => '',
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
