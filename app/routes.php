<?php

// Let them logout
Route::get('logout', function()
{
	Auth::logout();
	return Redirect::to('/')->with('message', 'You have successfully logged out.');
});

// Non-Secure routes
Route::controller('api' ,	'Core_ApiVersionOneController');
Route::controller('video',	'VideoController');
Route::controller('quotes',	'QuoteController');
Route::controller('actors',	'ActorController');

// Secure routes
/********************************************************************
 * General
 *******************************************************************/
Route::group(array('before' => 'auth'), function()
{
	Route::controller('user'	, 'UserController');
	Route::controller('messages', 'Core_MessageController');
	Route::controller('chat'	, 'Core_ChatController');
	Route::controller('github'	, 'Core_GithubController');
});

/********************************************************************
 * Access to contributor areas
 *******************************************************************/
Route::group(array('before' => 'auth|permissions:CONTRIBUTOR'), function()
{
	Route::controller('manage',			'ManageController');
});

/********************************************************************
 * Access to forum moderation
 *******************************************************************/
Route::group(array('before' => 'auth|permission:FORUM_MOD'), function()
{
	Route::controller('forum/moderation', 'Core_Forum_ModerationController');
});

/********************************************************************
 * Access to forum administration
 *******************************************************************/
Route::group(array('before' => 'auth|permission:FORUM_ADMIN'), function()
{
	Route::controller('forum/admin', 'Core_Forum_AdminController');
});

/********************************************************************
 * Access to the forums
 *******************************************************************/
Route::group(array('before' => 'auth|permission:FORUM_ACCESS'), function()
{
	Route::controller('forum/post'		, 'Core_Forum_PostController');
	Route::controller('forum/board'		, 'Core_Forum_BoardController');
	Route::controller('forum/category'	, 'Core_Forum_CategoryController');
	Route::controller('forum'			, 'Core_ForumController');
});

/********************************************************************
 * Access to the dev panel
 *******************************************************************/
Route::group(array('before' => 'auth|permission:SITE_ADMIN'), function()
{
	Route::controller('admin', 'AdminController');
});

// Landing page
Route::controller('/', 'HomeController');

require_once('start/local.php');
