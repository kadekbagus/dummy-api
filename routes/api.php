<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Person;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('person', function() {
    return Person::get();
});

Route::post('person', function(Request $request) {
    $newPerson = null;
    if (isset($request->first_name)) {
        $newPerson = new Person();
        $newPerson->first_name = $request->first_name;
        $newPerson->last_name = $request->last_name;
        $newPerson->gender = $request->gender;
        $newPerson->age = $request->age;
        $newPerson->email = $request->email;
        $newPerson->save();
    }

    return $newPerson;
});