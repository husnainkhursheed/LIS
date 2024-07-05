<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\CustomDropdownController;
use App\Http\Controllers\Admin\Setup\NoteController;
use App\Http\Controllers\Admin\Setup\TestController;
use App\Http\Controllers\Admin\Setup\DoctorController;
use App\Http\Controllers\Admin\Setup\SampleController;
use App\Http\Controllers\Admin\Setup\PatientController;
use App\Http\Controllers\Admin\Setup\PracticeController;
use App\Http\Controllers\ReportingandAnalyticsController;
use App\Http\Controllers\Admin\Setup\InstitutionController;
use App\Http\Controllers\Admin\Reports\TestReportController;
use App\Http\Controllers\Admin\Setup\CustomDropdownController;
use App\Http\Controllers\Admin\UserManagement\RolesController;
use App\Http\Controllers\Admin\UserManagement\UsersController;
use App\Http\Controllers\Admin\Setup\SenstivityItemsController;
use App\Http\Controllers\Admin\UserManagement\PermissionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');


Route::middleware(['auth'])->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permission.index');

    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RolesController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RolesController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/{id}', [RolesController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RolesController::class, 'destroy'])->name('roles.destroy');

    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

    Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
    Route::post('/practice', [PracticeController::class, 'store'])->name('practice.store');
    Route::get('/practice/{id}/edit', [PracticeController::class, 'edit'])->name('practice.edit');
    Route::post('/practice/{id}', [PracticeController::class, 'update'])->name('practice.update');
    Route::delete('/practice/{id}', [PracticeController::class, 'destroy'])->name('practice.destroy');

    /////////////////// husnian routes ///////////////
    Route::get('/institution', [InstitutionController::class, 'index'])->name('institution.index');
    Route::post('/institution', [InstitutionController::class, 'store'])->name('institution.store');
    Route::get('/institution/{id}/edit', [InstitutionController::class, 'edit'])->name('institution.edit');
    Route::post('/institution/{id}', [InstitutionController::class, 'update'])->name('institution.update');
    Route::delete('/institution/{id}', [InstitutionController::class, 'destroy'])->name('institution.destroy');

    Route::get('/doctor', [DoctorController::class, 'index'])->name('doctor.index');
    Route::post('/doctor', [DoctorController::class, 'store'])->name('doctor.store');
    Route::get('/doctor/{id}/edit', [DoctorController::class, 'edit'])->name('doctor.edit');
    Route::post('/doctor/{id}', [DoctorController::class, 'update'])->name('doctor.update');
    Route::delete('/doctor/{id}', [DoctorController::class, 'destroy'])->name('doctor.destroy');

    Route::get('/patient', [PatientController::class, 'index'])->name('patient.index');
    Route::post('/patient', [PatientController::class, 'store'])->name('patient.store');
    Route::get('/patient/{id}/edit', [PatientController::class, 'edit'])->name('patient.edit');
    Route::post('/patient/{id}', [PatientController::class, 'update'])->name('patient.update');
    Route::delete('/patient/{id}', [PatientController::class, 'destroy'])->name('patient.destroy');

    Route::get('/test', [TestController::class, 'index'])->name('test.index');
    Route::post('/test', [TestController::class, 'store'])->name('test.store');
    Route::get('/test/{id}/edit', [TestController::class, 'edit'])->name('test.edit');
    Route::post('/test/{id}', [TestController::class, 'update'])->name('test.update');
    Route::delete('/test/{id}', [TestController::class, 'destroy'])->name('test.destroy');

    Route::get('/note', [NoteController::class, 'index'])->name('note.index');
    Route::post('/note', [NoteController::class, 'store'])->name('note.store');
    Route::get('/note/{id}/edit', [NoteController::class, 'edit'])->name('note.edit');
    Route::post('/note/{id}', [NoteController::class, 'update'])->name('note.update');
    Route::delete('/note/{id}', [NoteController::class, 'destroy'])->name('note.destroy');

    Route::get('/profile', [SenstivityItemsController::class, 'index'])->name('profile.index');
    Route::post('/profile', [SenstivityItemsController::class, 'store'])->name('profile.store');
    Route::get('/profile/{id}/edit', [SenstivityItemsController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/{id}', [SenstivityItemsController::class, 'update'])->name('profile.update');
    Route::delete('/profile/{id}', [SenstivityItemsController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/sample', SampleController::class);

    Route::prefix('reports')->group(function () {
        Route::get('/test-reports', [TestReportController::class, 'index'])->name('test-reports.index');
        // Route::get('/test-reports/search', [TestReportController::class, 'search'])->name('test-reports.search');
        Route::post('/test-reports/{id}', [TestReportController::class, 'getreportforedit'])->name('test-reports.getreportforedit');
        // Route::get('/test-reports/{id}', [TestReportController::class, 'show'])->name('test-reports.show');
        Route::get('/test-reports/{id}/edit', [TestReportController::class, 'edit'])->name('test-reports.edit');
        Route::post('/save-reports', [TestReportController::class, 'saveReports'])->name('test-reports.saveReports');
        Route::delete('/test-reports/{id}', [TestReportController::class, 'destroy'])->name('test-reports.destroy');
        Route::post('/delink-test/{id}', [TestReportController::class, 'delinktest'])->name('test-reports.delinktest');
        Route::post('/complete-test', [TestReportController::class, 'completetest'])->name('test-reports.completetest');

        Route::post('/sensitivity/report', [TestReportController::class, 'getsensitivityitems'])->name('test-reports.getsensitivityitems');

        // sign report
        Route::post('/sign-report', [TestReportController::class, 'signReport'])->name('test-reports.signReport');
        // report notes
        Route::get('/fetch-notes-cytology', [TestReportController::class, 'fetchNotesCytology'])->name('fetch-notes-cytology');
        Route::get('/fetch-notes-urinalysis', [TestReportController::class, 'fetchNotesUrinalysis'])->name('fetch-notes-urinalysis');

        Route::get('/audit-traits', [TestReportController::class, 'auditTraits'])->name('audit-traits.index');

        Route::get('/processing-time', [ReportingandAnalyticsController::class, 'index'])->name('processingtime.index');

    });

    Route::post('/custom-dropdown/store', [CustomDropdownController::class, 'store'])->name('custom-dropdown.store');
    Route::get('custom-dropdown/names/{id}', [CustomDropdownController::class, 'getDropdownNames'])->name('custom-dropdown.getDropdownNames');
    Route::get('custom-dropdown/getvalues/{id}/edit', [CustomDropdownController::class, 'getvalues'])->name('custom-dropdown.getvalues');


    // generate pdf route
    Route::get('generate-pdf/{id}/{type}', [App\Http\Controllers\PDFController::class, 'generatePDF']);
    Route::get('generate-pdf/{id}', [App\Http\Controllers\PDFController::class, 'generatePDF1']);



    Route::get('verify/{token}', [UsersController::class, 'verify']);
    // Route::view('verify-view', 'emails.verify');
    Route::post('set-password', [UsersController::class, 'setPassword']);

    ////////////       end routes       /////////////////////

    // Route::view('/profile' , 'employee.profile');
    // Route::get('/profile', function () {
    //     $employee = \App\Models\MainEmployee::find(1);
    //     // dd($employee);
    //     // $practices = SetupPractice::where('is_active', 1)->get();
    //     // $genders = SetupGender::all();
    //     return view('employee.profile',compact('employee'));
    // });



});
