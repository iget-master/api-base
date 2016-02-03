<?php

namespace Iget\ApiBase\Http\Controllers\Auth;

use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * @param \Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogin(Request $request)
    {
        $success = false;

        if (Auth::attempt($request->only(['email', 'password']))) {
            $success = true;
            $token = Auth::getToken();
            $user_id = Auth::user()->id;
        }

        return response()->json(compact('success', 'token', 'user_id'));
    }
}