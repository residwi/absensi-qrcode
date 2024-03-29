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

Route::group(['middleware' => ['roles', 'auth'], 'roles' => ['admin', 'dosen'],], function () {
    Route::get('/', 'UserController@index');
    Route::get('/home', 'UserController@index')->name('home');

    Route::get('/profile', 'UserController@profile')->name('profile.users');
    Route::put('/profile/update', 'UserController@updateProfile')->name('profile.users.update');

    Route::get('/password', 'UserController@changePassword')->name('password.users');
    Route::put('/password/update', 'UserController@updatePassword')->name('password.users.update');

    //tabel jadwal dosen
    Route::get('/jadwaldosen', 'JadwalController@dosen')->name('jadwal.dosen.index');
    
    //Jadwal dosen
    Route::get('/jadwal/{id}/pertemuan', 'JadwalController@pertemuan')->name('jadwal.pertemuan');

    // download qr code
    Route::get('/jadwal/{id}/pertemuan/qrcode/download', 'JurnalController@qrcode')->name('jadwal.jurnal.qrcode');

    // absensi
    Route::get('/jadwal/{id}/pertemuan/{pertemuan}/absensi', 'AbsensiController@index')->name('jadwal.absensi.index');
    Route::get('api/jadwal/{id}/pertemuan/{pertemuan}/absensi/datatables', 'AbsensiController@getDatatables')->name('jadwal.absensi.datatables');
    Route::post('api/jadwal/{id}/pertemuan/{pertemuan}/absensi/update', 'AbsensiController@updateStatus')->name('jadwal.absensi.update');
});

Auth::routes(['register' => false, 'reset' => false, 'confirm' => false,]);

// Routing milik admin
Route::group([
    'middleware' => ['roles', 'auth'],
    'roles' => ['admin'],
], function () {
    //CRUD dosen
    Route::resource('/dosen', 'Admin\DosenController');

    //CRUD mahasiswa
    Route::resource('/mahasiswa', 'Admin\MahasiswaController');

    //CRUD matkul
    Route::resource('/matkul', 'Admin\MatkulController');

    //CRUD jadwal
    Route::resource('/jadwal', 'JadwalController');

    // add mahasiswa ke jadwal
    Route::post('/jadwal/{id}/mahasiswa/create', 'JadwalController@addJadwalMahasiswa')->name('admin.jadwal.mahasiswa.add');
    Route::delete('/jadwal/{id}/mahasiswa/delete/{mahasiswa}', 'JadwalController@deleteJadwalMahasiswa');

    // membuat jurnal pada jadwal
    Route::get('/jadwal/{id}/pertemuan/{pertemuan}/create', 'JurnalController@create')->name('jadwal.jurnal.create');
    Route::post('/jadwal/{id}/pertemuan/{pertemuan}/store', 'JurnalController@store')->name('jadwal.jurnal.store');

    Route::group(['prefix' => 'api'], function () {
        Route::get('/dosen/datatables', 'Admin\DosenController@getDatatables')->name('admin.datatables.dosen');
        Route::get('/dosen/search', 'Admin\DosenController@ajaxSearch')->name('admin.ajaxsearch.dosen');

        Route::get('/mahasiswa/datatables', 'Admin\MahasiswaController@getDatatables')->name('admin.datatables.mahasiswa');
        Route::get('/mahasiswa/search', 'Admin\MahasiswaController@ajaxSearch')->name('admin.ajaxsearch.mahasiswa');

        Route::get('/matkul/datatables', 'Admin\MatkulController@getDatatables')->name('admin.datatables.matkul');
        Route::get('/matkul/search', 'Admin\MatkulController@ajaxSearch')->name('admin.ajaxsearch.matkul');

        Route::get('/jadwal/datatables', 'JadwalController@getDatatables')->name('admin.datatables.jadwal');
        Route::get('/jadwal/{id}/mahasiswa/datatables', 'JadwalController@getDatatablesMahasiswa')->name('admin.datatables.jadwalmahasiswa');
    });
});