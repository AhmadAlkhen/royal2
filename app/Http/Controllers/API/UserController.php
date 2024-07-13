<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request ,User $users){

        try {

            $options = $request->input('options', []);

            $currentPage = $options['page'] ?? 1;
            $perPage = $options['itemsPerPage'] ?? 10;

            $usersQuery = $users->newQuery();

            if ($request->has('status')) {
                if($request->input('status') != '')
                $usersQuery->where('status', $request->input('status'));
            }

            if ($request->has('q')) {
                if($request->input('q') != '')
                $usersQuery->where('name','like','%'.$request->input('q').'%');
            }

            
            $users = $usersQuery
            
            ->paginate($perPage, ['*'], 'page', $currentPage);
            $totalUsers = $usersQuery->count();
            
           
            $totalPages = ceil($totalUsers / $perPage);

            return response()->json([
                'status' => true,
                'data' => $users,
                'totalUsers' => $totalUsers,
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
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6',
                    'role' => 'required|string',
                ]);
        
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 422);
                }

                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->role = $request->role;
                $user->status = $request->status;
                $user->save();

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


    public function getUser(Request $request){
        try {
            $user= User::where('id',$request->id)->first();
            return response()->json([
                'status' => true,
                'data' => $user,
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
     
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'email' => 'string|email|max:255|unique:users,email,' . $request->id,
                'password' => 'string|min:6',
                'role' => 'string',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $user = User::findOrFail($request->id);
     
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];
            
            if ($request->password && $request->password != "") {
                $data['password'] = Hash::make($request->password);
                $data['remember_token'] = Null;
            }
            $user->update($data);
     
     
         
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
        $user = User::findOrFail($request->id);

        $user->delete();
    
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
