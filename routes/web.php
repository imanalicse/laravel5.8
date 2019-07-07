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

Route::get('/callback', function () {
    echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";
});

//Converting Authorization Codes To Access Tokens
Route::get('/callback2', function (Request $request) {

    $http = new GuzzleHttp\Client(
        [
            'verify' => false,
            'defaults' => [
                'exceptions' => false
            ]
        ]);

    $response = $http->post('http://localhost:8000/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => 3,
            'client_secret' => 'MD94aUlZSqw6ctwjqxPYjFzfc9bWI66mI5IFLuIq',
            'redirect_uri' => 'http://localhost:8000/callback',
            'code' => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

Route::get('/get-token', function (Request $request) {

    $http = new GuzzleHttp\Client([
        'curl' => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
        'verify'=> false
    ]);


    $response = $http->get('http://localhost:8000/oauth/clients');
    echo "<pre>";
    print_r($response);
    echo "</pre>";
    die();
    return json_decode((string) $response->getBody(), true);
});

