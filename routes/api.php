<?php
Route::post('image/destroy', 'ImageController@destroy')->name('image.destroy');
Route::get('province/{province}/city', 'CityController@getCity')->name('province');
Route::get('city/{city}/district', 'DistrictController@getDistrict')->name('district');
Route::get('district/{district}/vilage', 'VilageController@getVilage')->name('vilage');