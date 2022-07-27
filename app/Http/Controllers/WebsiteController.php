<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WebsiteController extends Controller
{
    public function index()
    {
        // dd(auth()->user());
        if(auth()->check()){
            return redirect('/dashboard');
        }else{
            return redirect('/login');
        }
    }

}
