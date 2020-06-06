<?php

Route::middleware('guest:client')->namespace('Auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login.show-form');
    Route::post('login', 'LoginController@login')->name('login');
});

Route::middleware('auth:client')->group(function () {
    Route::get('/', 'HomeController@index')->name('index');
    Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

    Route::get('report', 'Report\ReportController@index')->name('report.index');
    Route::get('report/view-pdf', 'Report\ReportController@viewPdf')->name('report.view-pdf');

    Route::get('receipt-report', 'ReceiptReport\ReceiptReportController@index')->name('receipt-report.index');
    Route::get('viewpdf', 'ReceiptReport\ReceiptReportController@viewPdf')->name('receipt-report.viewpdf');

    Route::get('profile', 'Profile\ProfileController@showForm')->name('profile.show-form');
    Route::put('profile', 'Profile\ProfileController@update')->name('profile.update');

    Route::get('student-statistic', 'Student\StudentStatisticController@index')->name('student.statistic.index');

    Route::get('student/mbc', 'Student\StudentMBCController@index')->name('student.mbc.index');
    Route::post('student/mbc/data', 'Student\StudentMBCController@getData')->name('student.mbc.data');
    Route::get('{student}/id/print1', 'Student\StudentMBCController@showPrint')->name('student.mbc.print1');

    Route::as('student')->resource('certificate', 'Student\StudentCertificateController', ['except' => ['create', 'show']]);
    Route::post('student/certificate/data', 'Student\StudentCertificateController@getData')->name('student.certificate.data');
    Route::get('{student}/id/print2', 'Student\StudentCertificateController@showPrint')->name('student.certificate.print2');

    Route::get('student/tuition', 'Student\TuitionController@index')->name('student.tuition.index');
    Route::post('student/tuition/data', 'Student\TuitionController@getData')->name('student.tuition.data');
    Route::get('{student}/id/print', 'Student\TuitionController@showPrint')->name('student.tuition.print');

    Route::as('student')->resource('move-grades', 'Student\MoveGradesController', ['except' => ['create', 'show']]);
    Route::post('student/move-grades/data', 'Student\MoveGradesController@getData')->name('student.move-grades.data');


    Route::prefix('finance')->group(function () {
        Route::resource('voucher', 'Voucher\VoucherController', ['except' => ['create', 'show']]);
        Route::post('voucher/data', 'Voucher\VoucherController@getData')->name('voucher.data');
        Route::get('voucher/{id}', 'Voucher\VoucherController@getVoucherData')->name('voucher.get-data');

        Route::resource('transaction', 'Transaction\TransactionController', ['except' => ['show']]);
        Route::post('transaction/data', 'Transaction\TransactionController@getData')->name('transaction.data');

        Route::resource('petty-cash', 'PettyCash\PettyCashController', ['except' => ['create', 'show']]);

        Route::get('tuition/report', 'Tuition\TuitionReportController@index')->name('tuition.report.index');
        Route::post('tuition/note', 'Tuition\TuitionReportController@updateNote')->name('tuition.note.update');
        Route::post('tuition/report/data', 'Tuition\TuitionReportController@getData')->name('tuition.report.data');

        Route::get('recap', 'Recap\RecapController@index')->name('recap.index');
        Route::get('recap/{from}/{to}', 'Recap\RecapController@index')->name('recap');

        Route::prefix('salary')->namespace('Salary')->as('salary.')->group(function () {
            Route::resource('skim', 'SKIMController', ['except' => ['create', 'show', 'update']]);
            Route::put('skim', 'SKIMController@updateAll')->name('skim.update-all');

            Route::get('generate', 'SalaryController@generateStaffSalary')->name('generate');
            Route::get('income', 'SalaryController@incomeIndex')->name('income.index');
            Route::get('income/{id}/edit', 'SalaryController@incomeEdit')->name('income.edit');
            Route::put('income/{id}', 'SalaryController@incomeUpdate')->name('income.update');
            Route::get('deduction', 'SalaryController@deductionIndex')->name('deduction.index');
            Route::get('deduction/{id}/edit', 'SalaryController@deductionEdit')->name('deduction.edit');
            Route::put('deduction/{id}', 'SalaryController@deductionUpdate')->name('deduction.update');
            Route::get('payment', 'SalaryController@paymentIndex')->name('payment.index');
            Route::get('slip', 'SlipController@index')->name('slip.index');
            Route::post('slip/data', 'SlipController@getData')->name('slip.data');
            Route::get('slip/{id}/print', 'SlipController@print')->name('slip.print');
        });

        Route::prefix('progressive')->namespace('Progressive')->as('progressive.')->group(function () {
            Route::get('recap', 'RecapController@index')->name('recap.index');

            Route::get('payment', 'PaymentController@index')->name('payment.index');

            Route::get('slip', 'SlipController@index')->name('slip.index');
            Route::post('slip/data', 'SlipController@getData')->name('slip.data');
            Route::get('slip/{id}/print', 'SlipController@print')->name('slip.print');
        });

        Route::get('profit-sharing', 'ProfitSharing\ProfitSharingController@index')->name('profit-sharing.index');
        Route::get('profit-sharing/view-pdf', 'ProfitSharing\ProfitSharingController@viewPdf')->name('profit-sharing.view-pdf');

        Route::get('report', 'Finance\ReportController@index')->name('finance.report.index');
        Route::get('report/view-pdf', 'Finance\ReportController@viewPdf')->name('finance.report.view-pdf');
    });

    Route::get('dpu', 'DPU\DPUController@index')->name('dpu.index');

    Route::prefix('module')->as('module.')->namespace('Module')->group(function () {
        Route::get('statistic', 'ModuleStatisticController@index')->name('statistic.index');

        Route::resource('price', 'ModulePriceController', ['except' => ['create', 'show']]);
        Route::post('price/data', 'ModulePriceController@getData')->name('price.data');

        Route::resource('addition', 'ModuleAdditionController', ['except' => ['create', 'show']]);

        Route::resource('usage', 'ModuleUsageController', ['except' => ['create', 'show']]);

        Route::get('stock-recap', 'ModuleStockRecapController@index')->name('stock-recap.index');
        Route::post('stock-recap', 'ModuleStockRecapController@changeOpname')->name('stock-recap.change-opname');
    });

    Route::prefix('order')->as('order.')->namespace('Order')->group(function () {
        Route::get('statistic', 'OrderStatisticController@index')->name('statistic.index');

        Route::get('module', 'OrderModuleController@index')->name('module.index');

        Route::get('attribute', 'OrderAttributeController@index')->name('attribute.index');
        Route::get('attribute/{id}', 'OrderAttributeController@edit')->name('attribute.edit');
        Route::put('attribute/{id}', 'OrderAttributeController@update')->name('attribute.update');

        Route::get('certificate', 'CertificateController@index')->name('certificate.index');
        Route::get('certificate/{id}', 'CertificateController@edit')->name('certificate.edit');
        Route::put('certificate/{id}', 'CertificateController@update')->name('certificate.update');

        Route::get('stpb', 'STPBController@index')->name('stpb.index');
        Route::get('stpb/{id}', 'STPBController@edit')->name('stpb.edit');
        Route::put('stpb/{id}', 'STPBController@update')->name('stpb.update');

        Route::resource('atk', 'ATKController', ['except' => ['create', 'show']]);
    });

    Route::resource('student', 'Student\StudentController');
    Route::post('student/data', 'Student\StudentController@getData')->name('student.data');
    Route::post('student/{id}/set/out', 'Student\StudentController@setAsOut')->name('student.set.out');
    Route::post('student/{id}/set/active', 'Student\StudentController@setAsActive')->name('student.set.active');
    Route::post('student/{id}/scholarship/extend', 'Student\StudentController@extendScholarship')->name('student.scholarship.extend');
    Route::post('student/import/csv', 'Student\StudentController@importCsv')->name('student.import.csv');

    Route::as('student')->resource('trial-student', 'Student\TrialStudentController', ['except' => ['create']]);
    Route::post('student/trial-student/data', 'Student\TrialStudentController@getData')->name('student.trial-student.data');
    Route::post('trial-student/import/csv', 'Student\TrialStudentController@importCsv')->name('trial-student.import.csv');

    Route::resource('staff', 'Staff\StaffController', ['except' => ['create']]);

    Route::post('staff/data', 'Staff\StaffController@getData')->name('staff.data');
    Route::post('staff/import/csv', 'Staff\StaffController@importCsv')->name('staff.import.csv');

    Route::as('staff')->resource('absence', 'Staff\AbsenceController', ['except' => ['create', 'show']]);
    Route::post('staff/absence/data', 'Staff\AbsenceController@getData')->name('staff.absence.data');

    Route::resource('product', 'Product\ProductController', ['except' => ['create', 'show']]);
    Route::post('product/data', 'Product\ProductController@getData')->name('product.data');

    Route::resource('public-relation', 'PublicRelation\PublicRelationController', ['except' => ['create']]);
    Route::post('public-relation/data', 'PublicRelation\PublicRelationController@getData')->name('public-relation.data');
    Route::post('public-relation/import/csv', 'PublicRelation\PublicRelationController@importCsv')->name('public-relation.import.csv');

    Route::prefix('management')->namespace('Management')->as('management.')->group(function () {
        Route::prefix('user')->as('user.')->group(function () {
            Route::post('data', 'UserController@getData')->name('data');
            Route::get('change-password', 'UserController@changePassword')->name('change-password');
            Route::put('update-password', 'UserController@updatePassword')->name('update-password');
            Route::get('{id}/change-password', 'UserController@changeOtherPassword')->name('other.change-password');
            Route::put('{id}/update-password', 'UserController@updateOtherPassword')->name('other.update-password');
            Route::get('login-history', 'UserController@showLoginHistory')->name('login-history');
            Route::post('login-history/data', 'UserController@getLoginHistoryData')->name('login-history.data');
        });
        Route::resource('user', 'UserController', ['except' => ['create', 'show']]);

        Route::resource('role', 'RoleController', ['except' => ['show']]);
        Route::post('role/data', 'RoleController@getData')->name('role.data');
    });

    Route::prefix('preference')->as('preference.')->namespace('Preference')->group(function () {
        Route::get('/', 'PreferenceController@showForm')->name('edit');
        Route::put('/', 'PreferenceController@update')->name('update');
    });

    Route::prefix('upload-download')->as('upload-download.')->namespace('UploadDownload')->group(function () {
        Route::resource('/', 'UploadDownloadController', ['except' => ['create', 'show', 'destroy']]);
        Route::post('/data', 'UploadDownloadController@getData')->name('data');
        Route::delete('/{id}', 'UploadDownloadController@destroy')->name('destroy');
    });

    Route::get('logout', 'Auth\LoginController@logout')->name('logout');
});
