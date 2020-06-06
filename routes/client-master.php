<?php

Route::middleware('auth:client')->group(function () {
    Route::resource('department', 'DepartmentController', ['except' => ['create', 'show']]);
    Route::post('department/data', 'DepartmentController@getData')->name('department.data');

    Route::resource('staff-position', 'StaffPositionController', ['except' => ['create', 'show']]);
    Route::post('staff-position/data', 'StaffPositionController@getData')->name('staff-position.data');

    Route::resource('absence-reason', 'AbsenceReasonController', ['except' => ['create', 'show']]);
    Route::post('absence-reason/data', 'AbsenceReasonController@getData')->name('absence-reason.data');

    Route::resource('public-relation-status', 'PublicRelationStatusController', ['except' => ['create', 'show']]);
    Route::post('public-relation-status/data', 'PublicRelationStatusController@getData')->name('public-relation-status.data');

    Route::resource('grade', 'GradeController', ['except' => ['create', 'show']]);
    Route::post('grade/data', 'GradeController@getData')->name('grade.data');

    Route::resource('class-group', 'ClassGroupController', ['except' => ['create', 'show']]);
    Route::post('class-group/data', 'ClassGroupController@getData')->name('class-group.data');

    Route::resource('class', 'ClassController', ['except' => ['create', 'show', 'update']]);
    Route::post('class/update', 'ClassController@update')->name('class.update');

    Route::get('class-price/{id}', 'ClassPriceController@getClassPriceData')->name('class-price.get-data');

    Route::resource('media-source', 'MediaSourceController', ['except' => ['create', 'show']]);
    Route::post('media-source/data', 'MediaSourceController@getData')->name('media-source.data');

    Route::resource('student-phase', 'StudentPhaseController', ['except' => ['create', 'show']]);
    Route::post('student-phase/data', 'StudentPhaseController@getData')->name('student-phase.data');

    Route::resource('student-out-reason', 'StudentOutReasonController', ['except' => ['create', 'show']]);
    Route::post('student-out-reason/data', 'StudentOutReasonController@getData')->name('student-out-reason.data');

    Route::resource('student-note', 'StudentNoteController', ['except' => ['create', 'show']]);
    Route::post('student-note/data', 'StudentNoteController@getData')->name('student-note.data');

    Route::resource('special-allowance-group', 'SpecialAllowanceGroupController', ['except' => ['create', 'show']]);
    Route::post('special-allowance-group/data', 'SpecialAllowanceGroupController@getData')->name('special-allowance-group.data');

    Route::resource('special-allowance', 'SpecialAllowanceController', ['except' => ['create', 'show']]);
    Route::post('special-allowance/data', 'SpecialAllowanceController@getData')->name('special-allowance.data');
});
