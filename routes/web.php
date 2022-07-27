<?php

use App\Models\Chapter;
use App\Models\Module;
use App\Models\Question;
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

Route::get('/dashboard', 'Admin\AdminController@index')->middleware('auth');


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/data-reload', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--seed' => true]);
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--path' => 'vendor/laravel/passport/database/migrations', '--force' => true]);
    \Illuminate\Support\Facades\Artisan::call('passport:install');
    return redirect()->back();
});
