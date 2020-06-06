<?php

Route::middleware('auth:client')->group(function () {
    Route::get('report/view', 'ReportViewController@index')->name('view.index');
    Route::get('report/category/membership', 'ReportViewController@showMembershipCategory')->name('category.membership');
    Route::get('report/category/staff', 'ReportViewController@showStaffCategory')->name('category.staff');
    Route::get('report/category/humas', 'ReportViewController@showHumasCategory')->name('category.humas');
    Route::get('report/category/keuangan', 'ReportViewController@showKeuanganCategory')->name('category.keuangan');

    Route::prefix('membership')->namespace('Membership')->as('membership.')->group(function() {
        Route::get('trial-student', 'TrialStudentReportController@showFilter')->name('trial-student.show-filter');
        Route::post('trial-student/display-pdf', 'TrialStudentReportController@displayPdf')->name('trial-student.display-pdf');
        Route::post('trial-student/download-pdf', 'TrialStudentReportController@downloadPdf')->name('trial-student.download-pdf');
        Route::post('trial-student/download-excel', 'TrialStudentReportController@downloadExcel')->name('trial-student.download-excel');
        Route::post('trial-student/download-csv', 'TrialStudentReportController@downloadCsv')->name('trial-student.download-csv');

        Route::get('student', 'StudentReportController@showFilter')->name('student.show-filter');
        Route::post('student/display-pdf', 'StudentReportController@displayPdf')->name('student.display-pdf');
        Route::post('student/download-pdf', 'StudentReportController@downloadPdf')->name('student.download-pdf');
        Route::post('student/download-excel', 'StudentReportController@downloadExcel')->name('student.download-excel');
        Route::post('student/download-csv', 'StudentReportController@downloadCsv')->name('student.download-csv');

        Route::get('move-grades', 'MoveGradeReportController@showFilter')->name('move-grades.show-filter');
        Route::post('move-grades/display-pdf', 'MoveGradeReportController@displayPdf')->name('move-grades.display-pdf');
        Route::post('move-grades/download-pdf', 'MoveGradeReportController@downloadPdf')->name('move-grades.download-pdf');
        Route::post('move-grades/download-excel', 'MoveGradeReportController@downloadExcel')->name('move-grades.download-excel');
        Route::post('move-grades/download-csv', 'MoveGradeReportController@downloadCsv')->name('move-grades.download-csv');
    });

    Route::prefix('report/staff')->namespace('Staff')->as('staff.')->group(function() {
        Route::get('profil', 'ProfileStaffReportController@showFilter')->name('profil.show-filter');
        Route::post('profil/display-pdf', 'ProfileStaffReportController@displayPdf')->name('profil.display-pdf');
        Route::post('profil/download-pdf', 'ProfileStaffReportController@downloadPdf')->name('profil.download-pdf');
        Route::post('profil/download-excel', 'ProfileStaffReportController@downloadExcel')->name('profil.download-excel');
        Route::post('profil/download-csv', 'ProfileStaffReportController@downloadCsv')->name('profil.download-csv');

        Route::get('absensi', 'AbsensiStaffReportController@showFilter')->name('absensi.show-filter');
        Route::post('absensi/display-pdf', 'AbsensiStaffReportController@displayPdf')->name('absensi.display-pdf');
        Route::post('absensi/download-pdf', 'AbsensiStaffReportController@downloadPdf')->name('absensi.download-pdf');
        Route::post('absensi/download-excel', 'AbsensiStaffReportController@downloadExcel')->name('absensi.download-excel');
        Route::post('absensi/download-csv', 'AbsensiStaffReportController@downloadCsv')->name('absensi.download-csv');
    });

    Route::prefix('report/humas')->namespace('Humas')->as('humas.')->group(function() {
        Route::get('humas-detail', 'HumasReportController@showFilter')->name('humas-detail.show-filter');
        Route::post('humas-detail/display-pdf', 'HumasReportController@displayPdf')->name('humas-detail.display-pdf');
        Route::post('humas-detail/download-pdf', 'HumasReportController@downloadPdf')->name('humas-detail.download-pdf');
        Route::post('humas-detail/download-excel', 'HumasReportController@downloadExcel')->name('humas-detail.download-excel');
        Route::post('humas-detail/download-csv', 'HumasReportController@downloadCsv')->name('humas-detail.download-csv');
    });

    Route::prefix('report/keuangan')->namespace('Keuangan')->as('keuangan.')->group(function() {
        Route::get('voucher', 'VoucherReportController@showFilter')->name('voucher.show-filter');
        Route::post('voucher/display-pdf', 'VoucherReportController@displayPdf')->name('voucher.display-pdf');
        Route::post('voucher/download-pdf', 'VoucherReportController@downloadPdf')->name('voucher.download-pdf');
        Route::post('voucher/download-excel', 'VoucherReportController@downloadExcel')->name('voucher.download-excel');
        Route::post('voucher/download-csv', 'VoucherReportController@downloadCsv')->name('voucher.download-csv');
    });

    Route::prefix('report/keuangan')->namespace('Keuangan')->as('keuangan.')->group(function() {
        Route::get('penerimaan', 'PenerimaanReportController@showFilter')->name('penerimaan.show-filter');
        Route::post('penerimaan/display-pdf', 'PenerimaanReportController@displayPdf')->name('penerimaan.display-pdf');
        Route::post('penerimaan/download-pdf', 'PenerimaanReportController@downloadPdf')->name('penerimaan.download-pdf');
        Route::post('penerimaan/download-excel', 'PenerimaanReportController@downloadExcel')->name('penerimaan.download-excel');
        Route::post('penerimaan/download-csv', 'PenerimaanReportController@downloadCsv')->name('penerimaan.download-csv');
    });

    Route::prefix('report/keuangan')->namespace('Keuangan')->as('keuangan.')->group(function() {
        Route::get('petty-cash', 'PettyCashReportController@showFilter')->name('petty-cash.show-filter');
        Route::post('petty-cash/display-pdf', 'PettyCashReportController@displayPdf')->name('petty-cash.display-pdf');
        Route::post('petty-cash/download-pdf', 'PettyCashReportController@downloadPdf')->name('petty-cash.download-pdf');
        Route::post('petty-cash/download-excel', 'PettyCashReportController@downloadExcel')->name('petty-cash.download-excel');
        Route::post('petty-cash/download-csv', 'PettyCashReportController@downloadCsv')->name('petty-cash.download-csv');
    });

    Route::prefix('report/keuangan')->namespace('Keuangan')->as('keuangan.')->group(function() {
        Route::get('petty-cash', 'PettyCashReportController@showFilter')->name('petty-cash.show-filter');
        Route::post('petty-cash/display-pdf', 'PettyCashReportController@displayPdf')->name('petty-cash.display-pdf');
        Route::post('petty-cash/download-pdf', 'PettyCashReportController@downloadPdf')->name('petty-cash.download-pdf');
        Route::post('petty-cash/download-excel', 'PettyCashReportController@downloadExcel')->name('petty-cash.download-excel');
        Route::post('petty-cash/download-csv', 'PettyCashReportController@downloadCsv')->name('petty-cash.download-csv');
    });
});
?>