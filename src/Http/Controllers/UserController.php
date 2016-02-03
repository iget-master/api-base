<?php

namespace Iget\ApiBase\Http\Controllers;

use Iget\ApiBase\Http\Requests\User\CreateRequest;
use Iget\ApiBase\Http\Requests\User\UpdateRequest;
use Iget\ApiBase\Models\User;
use \Gate;
use \Log;

class UserController extends Controller
{
    /**
     * Create a new user controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $instance_id
     * @return \Illuminate\Http\Response
     */
    public function index($instance_id)
    {
        // If user has not permission to list users, log it and deny access (HTTP 403)
        if (Gate::denies('list-instance-users', [User::class, $instance_id])) {
            Log::info('Access denied for instance user listing.', [
                'auth_user' => auth()->user(),
                'request_ip' => request()->ip(),
                'instance_id' => $instance_id
            ]);

            abort(403);
        }

        return response()->json(User::fromInstance($instance_id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        return response()->json(User::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // If user has not permission to show user, log it and deny access (HTTP 403)
        if (Gate::denies('show', [User::class, $id])) {
            Log::info('Access denied for user showing.', [
                'auth_user' => auth()->user(),
                'origin_ip' => request()->ip(),
                'id' => $id
            ]);
            abort(403);
        }

        return response()->json(User::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Responsedd
     */
    public function update(UpdateRequest $request, $id)
    {
        // If user has not permission to show user, log it and deny access (HTTP 403)
        if (Gate::denies('update', [User::class, $id])) {
            Log::info('Access denied for user updating.', [
                'auth_user' => auth()->user(),
                'origin_ip' => request()->ip(),
                'id' => $id,
                'request' => $request->all(),
            ]);
            abort(403);
        }

        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'password']));

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Gate::denies('destroy', [User::class, $id])) {
            Log::info('Access denied for user destroy.', [
                'auth_user' => auth()->user(),
                'origin_ip' => request()->ip(),
                'id' => $id,
            ]);
            abort(403);
        }

        return response()->json([
            "success" => (bool) User::destroy($id)
        ]);
    }
}
