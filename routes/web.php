<?php

use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
    step 1: open external-drive > QB Software Do Not Entry > server
    step 2: open xampp-cotrol.exe
    step 3: start apache and mysql

    step 4: open external-drive > QB Software Do Not Entry > server > htdocs
    step 5: slect question_bank, press shift and right click, click open power_shell window
    step 6: paste the command >  php artisan serve
    step 7: visit http://127.0.0.1:8000
    step 8: done
*/



Route::get('/', 'WebsiteController@index')->name('index')->middleware('auth');

Route::get('/truncate', function () {
    if (request()->db) {
        DB::table(request()->db)->truncate();
        dd(request()->db, 'truncated');
    }
    if (request()->has('all')) {
        DB::table('modules')->truncate();
        DB::table('chapters')->truncate();
        DB::table('questions')->truncate();
        DB::table('question_papers')->truncate();
        DB::table('question_paper_questions')->truncate();
        DB::table('exams')->truncate();
        DB::table('exam_question_papers')->truncate();
        dd('all truncated');
    }
});

Route::get('/p/{p}', function ($p) {
    dd(Hash::make($p));
});

Route::get('/print', function ($p = null) {
    return view('invoice');
});

Route::get('/dashboard', 'Admin\AdminController@index');

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/data-reload', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--seed' => true]);
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--path' => 'vendor/laravel/passport/database/migrations', '--force' => true]);
    \Illuminate\Support\Facades\Artisan::call('passport:install');
    return redirect()->back();
});

Route::any('/logins', function () {
    // \Illuminate\Support\Facades\Mail::raw('your verification code is :' . rand(100000, 999999), function ($message) {
    //     $message->to(request()->email)
    //         ->subject('varification code');
    // });
    // $mail = \Illuminate\Support\Facades\Mail::to(request()->email)
    // // ->bcc('nabilfarhaan77@gmail.com')
    // // ->cc('nabilfarhaan77@gmail.com')
    // ->send(new VerificationMail());
    dd(session()->all(), auth()->user(), auth()->guard('web'), auth()->guard('api') );
    return request()->all();
})->name('route name');
