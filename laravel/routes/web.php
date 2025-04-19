<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/call-python', function () {
    $data = ['example' => 'data from Laravel'];
    $response = Http::post('http://127.0.0.1:5000/process', $data); // Call Python server
    return $response->json(); // Return Python's response
});

Route::post('/send-tweet', function (Request $request) {
    $message = $request->input('message');
    $response = Http::post('http://127.0.0.1:5000/post-tweet', ['message' => $message]);
    return $response->json();
});
