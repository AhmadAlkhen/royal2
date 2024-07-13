<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Post_report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostReportController extends Controller
{
    public function index(Request $request ,Post_report $reports){

        try {

            $user= Auth::user();
            $options = $request->input('options', []);

            $currentPage = $options['page'] ?? 1;
            $perPage = $options['itemsPerPage'] ?? 10;

            $postsQuery = $reports->newQuery();


            $posts = $postsQuery
            ->with(['creator:id,name','post'])
            ->paginate($perPage, ['*'], 'page', $currentPage);

            $totalPosts = $postsQuery->count();
            
            $totalPages = ceil($totalPosts / $perPage);

            return response()->json([
                'status' => true,
                'data' => $posts,
                'totalPosts' => $totalPosts,
                'totalPages' => $totalPages,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }



    public function store(Request $request){

            try {

                $validator = Validator::make($request->all(), [
                    'post_id' => 'required',
                    'note' => 'string',
                ]);
        
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 422);
                }
                $user= Auth::user();

                $report = new Post_report();
                $report->post_id = $request->post_id;
                $report->note = $request->note;
                $report->applied_by = $user->id;

                $report->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Added successfuly'
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }

    }

    public function getReport(Request $request){
        try {
            $post = Post_report::with(['creator:id,name','post'])->findOrFail($request->id);
    

            return response()->json([
                'status' => true,
                'data' => $post,
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    

}
