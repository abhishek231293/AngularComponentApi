<?php
Route::get('storiesmap', 'StoriesmapController@index')->name('storiesmap');
Route::post('/storiesmap/saveimagemap', 'StoriesmapController@saveImageMap')->name('storiesmap.saveImageMap');
Route::post('/storiesmap/get-atm-details', 'StoriesmapController@getAtmDetails')->name('storiesmap.get-atm-details');
Route::post('/storiesmap/get-branch-details', 'StoriesmapController@getBranchDetails')->name('storiesmap.get-branch-details');
Route::post('/storiesmap/consolidated-search', 'StoriesmapController@consolidatedSearch')->name('consolidatedSearch');