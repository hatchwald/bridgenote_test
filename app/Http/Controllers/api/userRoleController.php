<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class userRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'user_id' => 'required',
            'status' => 'required|in:active,inactive',
            'position' => 'required'
        ]);


        if ($validator->fails()) {
            return response($validator->messages(), 500);
        }
        $user_data = User::find($request->user_id);
        if (empty($user_data)) {
            return response('user not found', 404);
        }



        if (!empty($user_data->role_id)) {
            return response('operation cannot process', 500);
        }
        $roles = UserRole::create([
            'status' => $request->status,
            'position' => $request->position,
        ]);


        $user_data->role_id = $roles->id;
        $user_data->save();
        return response('success create roles', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function show(UserRole $userRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRole $userRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserRole $userRole)
    {
        $validator = Validator::make($request->json()->all(), [
            'status' => 'required|in:active,inactive',
            'position' => 'required'
        ]);


        if ($validator->fails()) {
            return response($validator->messages(), 500);
        }

        $roles = UserRole::where('user_id', $userRole->user_id);
        $roles->update([
            'status' => $request->status,
            'position' => $request->position
        ]);

        return response('success updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRole $userRole)
    {
        $roles = UserRole::where('user_id', $userRole->user_id);
        $user_data = User::where('role_id', $userRole->user_id);
        $user_data->update([
            'role_id' => null
        ]);
        $roles->delete();

        return response('success delete role', 200);
    }
}
