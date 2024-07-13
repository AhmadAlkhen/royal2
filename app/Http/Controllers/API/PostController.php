<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(Request $request ,Post $posts){

        try {

            $user= Auth::user();
            $options = $request->input('options', []);

            $currentPage = $options['page'] ?? 1;
            $perPage = $options['itemsPerPage'] ?? 10;

            $postsQuery = $posts->newQuery();

            if ($request->has('status')) {
                if($request->input('status') != '')
                $postsQuery->where('status', $request->input('status'));
            }

            if ($request->has('q')) {
                if($request->input('q') != '')
                $postsQuery->where('title','like','%'.$request->input('q').'%')
                ->orWhere('content','like','%'.$request->input('q').'%');
            }


            if($user->role != 'admin'){
                $postsQuery->where('created_by', $user->id);
            }

            $posts = $postsQuery
            ->with(['creator:id,name'])
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
                    'title' => 'required|string|max:255',
                    'content' => 'required|string',
                ]);
        
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 422);
                }
                $user= Auth::user();

                $post = new Post();
                $post->title = $request->title;
                $post->content = $request->content;
                $post->created_by = $user->id;

                $post->save();

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

    public function getPost(Request $request){
        try {
            $user = Auth::user();
            $post = Post::with(['creator:id,name'])->findOrFail($request->id);
    
            if ($user->role != 'admin' && $post->created_by != $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
    
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
    

    public function update(Request $request){
        try {

            Log::alert($request->all());

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'title' => 'string|max:255',
                'content' => 'string',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
    
            $user = Auth::user();
            $post = Post::findOrFail($request->id);
    
            // Check if the user has permission to update this post
            if ($user->role != 'admin' && $post->created_by != $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
    
            // Prepare the data to update
            $data = [
                'title' => $request->title,
                'content' => $request->content,
            ];
            
            $post->update($data);
    
            return response()->json([
                'status' => true,
                'message' => 'Updated successfully'
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    

   public function delete(Request $request){
    try {
        $user = Auth::user();

        $post = Post::findOrFail($request->id);
        // Check if the user has permission to update this post

        if ($user->role != 'admin' && $post->created_by != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $post->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'Deleted Successfully',
             
            ], 200);

    } catch (\Exception $e) {

        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }

}



}
