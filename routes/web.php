<?php

use App\Http\Controllers\IssueController;
use App\Http\Controllers\LegacyReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/issues', [IssueController::class, 'index']);
Route::get('/issues/{issue}', [IssueController::class, 'show']);
Route::get('/reports/summary/{tenant}', [LegacyReportController::class, 'summary']);
