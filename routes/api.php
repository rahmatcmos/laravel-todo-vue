<?php

Route::group([ 'prefix'    => 'v1',
			   'namespace' => 'V1\Controllers',
], function () {
	Route::group([ 'middleware' => 'access_token' ], function () {
	});
	Route::group([ ], function () {
		Route::post('register', 'AuthController@register');
	});
});