<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->user()->id);
        $token = $user->createToken('accessToken')->accessToken;
        return view('dashboard',compact('token'));
    }


}
