<?php

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

Route::get('/', function () {
    // return view('welcome');
    // return redirect('/document');
    return redirect("/index");
});
Route::get('line_callback', 'LineController@callback');
Route::get('line_test', 'LineController@testSend');
Route::get('line_unset', 'LineController@unset');
Route::post('post-login', 'AuthController@postLogin'); 
Route::post('post-registration', 'AuthController@postRegistration'); 
Route::get('logout', 'AuthController@logout');
Route::get('document/sharing/{token}', 'SharingDocumentController@show')
->name('document.sharing');
Route::get('/migrate', 'UserController@migrate')->name('migrate');
Route::middleware(['web','auth'])->group(function(){

    Route::get("/editor", "OnlineDocumentController@editor");
    Route::get("/mydocument", "OnlineDocumentController@documentList");
    Route::get('online-document/{id}', 'OnlineDocumentController@delete');

    // document
    Route::resource('document', 'DocumentController');
    Route::get('document/inbox', 'DocumentController@inbox');
    Route::get('document/sentbox', 'DocumentController@sentbox');

    // Create comment for docuemnt
    Route::post('document/{document}/comment', 'DocumentController@comment')
        ->name('document.comment');

    // Respond document (approve / read)
    Route::put('document/{document}/respond', 'DocumentController@respond')
        ->name('document.respond');

    // Update Docuemnt Status (from draft to pending)
    Route::put('document/{document}/send', 'DocumentController@send')
        ->name('document.send');

    // Create link for docuemnt to publish other
    Route::post('document/{id}/publish', 'DocumentController@createPublishLink')
        ->name('document.publish');

    // Send Link to user by email
    Route::post('document/{id}/sendmail', 'DocumentController@sendMail')
        ->name('document.sendmail');

    // Resourse Cabinet 
    Route::resource('cabinet', 'CabinetController');
    Route::get('cabinet/{id}/destroy', 'CabinetController@destroy')->name('cabinet.destroy');
    // Get user who can access cabinet
    Route::get('cabinet/{cabinet}/permission', 'CabinetController@permission')
        ->name('cabinet.permission.index');
    // Update user who can access
    Route::put('cabinet/{cabinet}/permission', 'CabinetController@updatePermission')
        ->name('cabinet.permission.update');

    // Get folders by cabinet id
    Route::get('cabinet/{cabinet_id}/folder', 'CabinetController@indexFolder')
        ->name('cabinet.folder.index');
    // Create folder binding with cabinet id
    Route::post('cabinet/{cabinet}/folder', 'CabinetController@storeFolder')
        ->name('cabinet.folder.store');
    // Create folder Form
    Route::get('cabinet/{cabinet_id}/folder/create', 'CabinetController@createFolder')
        ->name('cabinet.folder.create');
    // Update folder
    Route::put('cabinet/folder/{folder_id}', 'CabinetController@updateFolder')
        ->name('cabinet.folder.update');
    // Update folder form
    Route::get('cabinet/folder/{folder}/edit', 'CabinetController@editFolder')
        ->name('cabinet.folder.edit');

    // Route::resource('folder', 'FolderController');
    Route::resource('officer', 'OfficerController');
    Route::post('officer/import', 'OfficerController@import')
        ->name("officer.import");
    Route::get('officer/download/template', function(){
        return response()->download(
            storage_path("import-template.csv")
        );
    });
    //$2y$10$DKkINgIhh1VgViD9sEEyRuGVtmL1sUV98KlTELZqalnvETt1KN5Hu
    
    // user profile
    Route::get('profile', 'UserController@edit')
        ->name('user.profile');
    Route::put('profile', 'UserController@update')
        ->name('user.update');

    Route::get('dashboard', 'DashBoardController@index');
    Route::get('index', 'MenuHomeController@index');
    Route::get('receives/index', 'ReceiveController@index')
    ->name('receive');
    Route::get('receives/received', 'ReceiveController@received')
    ->name('received');
    Route::get('receives/export', 'ReceiveController@export')
    ->name('ExportPDF');
    Route::get('receives/pdf', 'ReceiveController@pdf')
    ->name('pdf');

});

Route::post('reset_password', 'OfficerController@password_reset')->name('password_reset');

// donload file
Route::get('file/{prefix}/{id}', 'FileController@downloadFile')
    ->name('attachment.download');

// Route::resource('cabinet', 'CabinetController');

Auth::routes();

Route::get('/home', function(){
    return redirect('/');
});
