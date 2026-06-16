<?php

use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/about', [PublicPageController::class, 'about'])->name('about');
Route::get('/about/details', [PublicPageController::class, 'aboutDetails'])->name('about.details');
Route::get('/contact', [PublicPageController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicPageController::class, 'storeContact'])->name('contact.store');
Route::get('/masjids', [PublicPageController::class, 'masjids'])->name('masjids.index');
Route::get('/masjids/{masjid}', [PublicPageController::class, 'showMasjid'])->name('masjids.show');
Route::get('/map', [PublicPageController::class, 'map'])->name('map');
Route::get('/timings', [PublicPageController::class, 'timings'])->name('timings.index');
Route::get('/timings/details', [PublicPageController::class, 'timingsDetails'])->name('timings.details');
Route::get('/timings/juma-eid', [PublicPageController::class, 'jumaEid'])->name('timings.juma');
Route::get('/timings/juma-eid/{masjid}', [PublicPageController::class, 'jumaEidDetails'])->name('timings.juma.details');

Route::get('/admin', [AdminPageController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/masjids', [AdminPageController::class, 'masjids'])->name('admin.masjids');
Route::get('/admin/add-masjid', [AdminPageController::class, 'addMasjid'])->name('admin.add-masjid');
Route::post('/admin/add-masjid', [AdminPageController::class, 'storeMasjid'])->name('admin.store-masjid');
Route::get('/admin/edit-masjid/{masjid}', [AdminPageController::class, 'editMasjid'])->name('admin.edit-masjid');
Route::put('/admin/edit-masjid/{masjid}', [AdminPageController::class, 'updateMasjid'])->name('admin.update-masjid');
Route::post('/admin/delete-masjid', [AdminPageController::class, 'deleteMasjid'])->name('admin.delete-masjid');
Route::get('/admin/timings', [AdminPageController::class, 'timings'])->name('admin.timings');
Route::get('/admin/settings', [AdminPageController::class, 'settings'])->name('admin.settings');
Route::post('/admin/settings', [AdminPageController::class, 'updateSettings'])->name('admin.update-settings');
