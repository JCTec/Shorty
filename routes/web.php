<?php

Route::get('/', 'ShortyController@index')->name('url.index');

Route::post('/maker', 'ShortyController@maker')->name('url.maker');
Route::post('/bulker', 'ShortyController@bulker')->name('url.bulker');

Route::post('/download', 'ShortyController@download')->name('url.download');


Route::get('/{url}', 'ShortyController@search')->name('url.search');

Route::prefix('api')->group(function() {
    Route::get('/make', 'ShortyPublicController@create')->name('url.create');
    Route::post('/bulk', 'ShortyPublicController@bulk')->name('url.bulk');

});




