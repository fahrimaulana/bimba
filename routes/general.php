<?php

Route::prefix('session')->namespace('Session')->as('session.')->group(function () {
    Route::post('locked-period/change', 'SessionController@changeLockedPeriod')->name('locked-period.change');
});
