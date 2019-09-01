<?php

use Illuminate\Http\Request;

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

Route::get('/invitations', 'InvitationController@index');
Route::get('/invitations/sent', 'InvitationController@sent');
Route::get('/invitations/received', 'InvitationController@received');
Route::put('/invitations/sand', 'InvitationController@sand');
Route::get('/invitations/accept/{id}', 'InvitationController@accept');
Route::get('/invitations/decline/{id}', 'InvitationController@decline');
Route::get('/invitations/cancel/{id}', 'InvitationController@cancel');
