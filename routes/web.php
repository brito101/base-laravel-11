<?php

use App\Http\Controllers\Admin\ACL\PermissionController;
use App\Http\Controllers\Admin\ACL\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ChangelogController;
use App\Http\Controllers\Admin\Chat\MessageController;
use App\Http\Controllers\Admin\KanbanController;
use App\Http\Controllers\Admin\OperationController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StepController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('admin')->name('admin.')->group(function () {

        /** Chat */
        Route::get('chat/read', [MessageController::class, 'read']);
        Route::get('chat', [MessageController::class, 'index'])->name('chat.index');
        Route::post('chat', [MessageController::class, 'store'])->name('chat.store');

        /** Chart home */
        Route::get('/chart', [AdminController::class, 'chart'])->name('home.chart');

        Route::group(['middleware' => ['log']], function () {
            /** Home */
            Route::get('/', [AdminController::class, 'index'])->name('home');

            /** Changelog */
            Route::get('/changelog', [ChangelogController::class, 'index'])->name('changelog');

            /** Users */
            Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::resource('users', UserController::class);

            /** Tools */
            Route::resource('tools', ToolController::class);
            Route::delete('/tools/image-delete/{id}', [ToolController::class, 'imageDelete'])->name('tools-image-delete');
            Route::delete('/tools/file-delete/{id}', [ToolController::class, 'fileDelete'])->name('tools-file-delete');

            /** Teams */
            Route::resource('teams', TeamController::class);

            /** Operations */
            Route::get('/operations/timeline/{id}', [OperationController::class, 'timeline'])->name('operations.timeline');
            Route::get('operations/ongoing', [OperationController::class, 'ongoing'])->name('operations.ongoing');
            Route::resource('operations', OperationController::class);
            Route::delete('/operations/file-delete/{id}', [OperationController::class, 'fileDelete'])->name('operations-file-delete');

            /** Kanban */
            Route::get('/operations/kanban/{id}', [KanbanController::class, 'index'])->name('kanban.index');
            Route::post('kanban-ajax-update/{id}', [KanbanController::class, 'update'])->name('kanban.update');
            Route::post('/kanban-store-action/{id}', [KanbanController::class, 'storeAction'])->name('kanban.store.action');
            Route::get('/kanban-update-actions/{id}', [KanbanController::class, 'updateActions'])->name('kanban.update.actions');
            Route::delete('/kanban-delete-actions/{id}', [KanbanController::class, 'deleteAction'])->name('kanban.delete.actions');

            /** Reports */
            Route::delete('/reports/file-delete/{id}', [ReportController::class, 'fileDelete'])->name('reports-file-delete');
            Route::resource('reports', ReportController::class);

            /**
             * Settings
             */

            /** Organizations */
            Route::resource('organizations', OrganizationController::class);
            Route::resource('steps', StepController::class);

            /**
             * ACL
             */
            /** Permissions */
            Route::resource('permission', PermissionController::class);

            /** Roles */
            Route::get('role/{role}/permission', [RoleController::class, 'permissions'])->name('role.permissions');
            Route::put('role/{role}/permission/sync', [RoleController::class, 'permissionsSync'])->name('role.permissionsSync');
            Route::resource('role', RoleController::class);
        });
    });
});

/** Web */
/** Home */
// Route::get('/', [SiteController::class, 'index'])->name('home');
Route::get('/', function () {
    return redirect('admin');
});

Auth::routes([
    'register' => false,
]);

// Route::fallback(function () {
//     return view('admin.404');
// });
