<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\AccountController;
use App\Http\Controllers\Settings\ProfileController;

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

/* #region Defining our first routes */

Route::get('contacts', function () {
    return "<h1>All Contacts</h1>";
});


/*
Route::get('/contacts/create', function () {
    return "<h1>Add new contact</h1>";
});*/
/* #endregion */

/* #region Routes Parameters */
/*Route::get('/contacts/{id}', function ($contactId) {
    return 'Contact ' . $contactId;
});*/
// Option Parameters
/*Route::get('/companies/{name?}', function ($companyName = null) {
    if ($companyName) {
        return '<h1>Company ' . $companyName . '</h1>';
    } else {
        return '<h1>All Companies</h1>';
    }
});*/
/* #endregion */

/* #region Constraining the route parameter format using Laravel regular expression*/
/*Route::get('/contacts/{id}', function ($contactId) {
    return 'Contact ' . $contactId;
})->whereNumber('id');*/
// Option Parameters
/*Route::get('/companies/{name?}', function ($companyName = null) {
    if ($companyName) {
        return '<h1>Company ' . $companyName . '</h1>';
    } else {
        return '<h1>All Companies</h1>';
    }
})->whereAlphaNumeric('name');*/
/* #endregion */

/* #region Named routes */
/*Route::get('/', function () {
    $html = "
    <h1>Contact App</h1>
    <div>
        <a href=' " . route('contacts.index') . "'>All contacts</a>
        <a href=' " . route('contacts.create') . "'>Add contact</a>
        <a href=' " . route('contacts.show', 1) . "'>Show contact</a>
    </div>
    ";
    return $html;
});
*/
/*
Route::get('/contact', function () { // here I change contacts to contact and still work
    return "<h1>All Contacts</h1>";
})->name('contacts.index');
*/
/*
Route::get('/contacts/create', function () {
    return "<h1>Add new contact</h1>";
})->name('contacts.create');
Route::get('/contacts/{id}', function ($contactId) {
    return 'Contact ' . $contactId;
})->whereNumber('id')->name('contacts.show');
*/
/* #endregion */

/* #region Route groups */
/*
Route::prefix('admin')->group(function(){

Route::get('/contact', function () { // here I change contacts to contact and still work
    return "<h1>All Contacts</h1>";
})->name('contacts.index');
Route::get('/contacts/create', function () {
    return "<h1>Add new contact</h1>";
})->name('contacts.create');
Route::get('/contacts/{id}', function ($contactId) {
    return 'Contact ' . $contactId;
})->whereNumber('id')->name('contacts.show');

});
*/
/* #endregion */

/* #region Fallback routes */
Route::fallback(function () {
    return "<h1>Sorry, the page does not exist</h1>";
});
/* #endregion */

/* #region Displaying data in the Blade template*/
/*Route::get('/', function () {
    return view('welcome');
});*/
/* #endregion */
/* #region Grouping the controller routes */
/*Route::controller(ContactController::class)->name('contacts.')->group(function(){
    Route::get('/contacts', 'index')->name('index');
    Route::get('/contacts/create', 'create')->name('create');
    Route::get('/contacts/{id}', 'show')->whereNumber('id')->name('show');
});*/
/* #endregion */
/* #region Single Action Controller */
Route::get('/', WelcomeController::class);
/* #endregion */
//Route::resource('/companies', CompanyController::class);
Route::resources([
    '/tags' => TagController::class,
    '/tasks' => TaskController::class
]);
Route::resource('/activities', ActivityController::class)->parameters([
    'activities' => 'active',
]);
//Route::resource('/contacts', ContactController::class);
Route::resources([
    '/contacts'  => ContactController::class,
    '/companies' => CompanyController::class
]);
Route::delete('/contacts/{contact}/restore', [ContactController::class, 'restore'])->withTrashed()->name('contacts.restore');
Route::delete('/contacts/{contact}/force-delete', [ContactController::class, 'forceDelete'])->withTrashed()->name('contacts.force-delete');

Route::delete('/companies/{company}/restore', [CompanyController::class, 'restore'])->withTrashed()->name('companies.restore');
Route::delete('/companies/{company}/force-delete', [CompanyController::class, 'forceDelete'])->withTrashed()->name('companies.force-delete');

/*
Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
Route::post('/contacts/{id}', [ContactController::class, 'update'])->name('contacts.update');
Route::get('/contacts/{id}/edit', [ContactController::class, 'edit'])->whereNumber('id')->name('contacts.edit');
Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->whereNumber('id')->name('contacts.destroy');
*/

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/settings/account', [AccountController::class, 'index'])->name('settings.account');

Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('settings.profile.edit');
Route::put('/settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');

