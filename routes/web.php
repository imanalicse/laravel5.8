<?php

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

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Passport routing
//Redirecting for authorization
Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => 3,
        'redirect_uri' => 'http://localhost:8000/callback',
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect('http://localhost:8000/oauth/authorize?'.$query);
});


//Converting Authorization Codes To Access Tokens
Route::get('/callback2', function (Request $request) {

    $client = new GuzzleHttp\Client(['base_uri' => 'http://localhost:8000/']);

    $response = $client->request('POST','oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => 3,
            'client_secret' => 'MD94aUlZSqw6ctwjqxPYjFzfc9bWI66mI5IFLuIq',
            'redirect_uri' => 'http://localhost:8000/callback',
            'code' => $request->code,
        ],
    ], ['verify' => false]);

    return json_decode((string) $response->getBody(), true);
});


Route::get('/guzzle/example/get', function (Request $request) {

    $client = new GuzzleHttp\Client(['base_uri' => 'https://jsonplaceholder.typicode.com/']);
    $response = $client->request('GET', 'posts', ['verify' => false]);


//    $client = new GuzzleHttp\Client(['base_uri' => 'http://localhost:8000/']);
//    $response = $client->request('GET', 'oauth/clients', ['verify' => false]);

    return json_decode((string) $response->getBody(), true);
});

