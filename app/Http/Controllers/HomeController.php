<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (request()->has('token')) {
            $id = explode('_', request()->token)[2];
            $user = User::where('id', $id)->first();

            if ($user) {
                auth()->guard('web')->login($user);
                return redirect('/dashboard');
            }
        } elseif (auth()->guard('web')->check()) {
            dd(Auth::guard('web')->user(), request()->all());
            return redirect('/dashboard');
        } else {
            return redirect('/login');
        }
    }
}
