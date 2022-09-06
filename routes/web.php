<?php

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

Route::get('/', function () {
return view('welcome');

//    $SERVER_API_KEY = 'dxzdlIrFQ7mgbV4tc08ix1:APA91bGjyqHGTzwHx-yiwz4YvOlA28JlgdEgyslD_tv5XD_l39Q0XF6bD6YlHzUQQeVUgfBIaYPTbR3EyOeWgMbmUguIVnMo-yrkj2o-LKtfn4joj0SIgid2txNQNKvPz96RhMEHWVAi';
//
//    $token_1 = 'APA91bGjyqHGTzwHx-yiwz4YvOlA28JlgdEgyslD_tv5XD_l39Q0XF6bD6YlHzUQQeVUgfBIaYPTbR3EyOeWgMbmUguIVnMo-yrkj2o-LKtfn4joj0SIgid2txNQNKvPz96RhMEHWVAi';
//
//    $data = [
//
//        "registration_ids" => [
//            $token_1
//        ],
//
//        "notification" => [
//
//            "title" => 'Welcome',
//
//            "body" => 'Description',
//
//            "sound"=> "default" // required for sound on ios
//
//        ],
//
//    ];
//
//    $dataString = json_encode($data);
//
//    $headers = [
//
//        'Authorization: key=' . $SERVER_API_KEY,
//
//        'Content-Type: application/json',
//
//    ];
//
//    $ch = curl_init();
//
//    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//
//    curl_setopt($ch, CURLOPT_POST, true);
//
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
//
//    $response = curl_exec($ch);
//
//    dd($response);

});
