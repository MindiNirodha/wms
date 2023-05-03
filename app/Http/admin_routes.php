<?php
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

/* ================== Homepage ================== */
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::auth();

/* ================== Access Uploaded Files ================== */
Route::get('files/{hash}/{name}', 'LA\UploadsController@get_file');

/*
|--------------------------------------------------------------------------
| Admin Application Routes
|--------------------------------------------------------------------------
*/

$as = "";
if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
	$as = config('laraadmin.adminRoute').'.';
	
	// Routes for Laravel 5.3
	Route::get('/logout', 'Auth\LoginController@logout');
}

Route::group(['as' => $as, 'middleware' => ['auth', 'permission:ADMIN_PANEL']], function () {
	
	/* ================== Dashboard ================== */
	
	Route::get(config('laraadmin.adminRoute'), 'LA\DashboardController@index');
	Route::get(config('laraadmin.adminRoute'). '/dashboard', 'LA\DashboardController@index');
	
	/* ================== Users ================== */
	Route::resource(config('laraadmin.adminRoute') . '/users', 'LA\UsersController');
	Route::get(config('laraadmin.adminRoute') . '/user_dt_ajax', 'LA\UsersController@dtajax');
	
	/* ================== Uploads ================== */
	Route::resource(config('laraadmin.adminRoute') . '/uploads', 'LA\UploadsController');
	Route::post(config('laraadmin.adminRoute') . '/upload_files', 'LA\UploadsController@upload_files');
	Route::get(config('laraadmin.adminRoute') . '/uploaded_files', 'LA\UploadsController@uploaded_files');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_caption', 'LA\UploadsController@update_caption');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_filename', 'LA\UploadsController@update_filename');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_public', 'LA\UploadsController@update_public');
	Route::post(config('laraadmin.adminRoute') . '/uploads_delete_file', 'LA\UploadsController@delete_file');
	
	/* ================== Roles ================== */
	Route::resource(config('laraadmin.adminRoute') . '/roles', 'LA\RolesController');
	Route::get(config('laraadmin.adminRoute') . '/role_dt_ajax', 'LA\RolesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_module_role_permissions/{id}', 'LA\RolesController@save_module_role_permissions');
	
	/* ================== Permissions ================== */
	Route::resource(config('laraadmin.adminRoute') . '/permissions', 'LA\PermissionsController');
	Route::get(config('laraadmin.adminRoute') . '/permission_dt_ajax', 'LA\PermissionsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_permissions/{id}', 'LA\PermissionsController@save_permissions');
	
	/* ================== Departments ================== */
	Route::resource(config('laraadmin.adminRoute') . '/departments', 'LA\DepartmentsController');
	Route::get(config('laraadmin.adminRoute') . '/department_dt_ajax', 'LA\DepartmentsController@dtajax');
	
	/* ================== Employees ================== */
	Route::resource(config('laraadmin.adminRoute') . '/employees', 'LA\EmployeesController');
	Route::get(config('laraadmin.adminRoute') . '/employee_dt_ajax', 'LA\EmployeesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/change_password/{id}', 'LA\EmployeesController@change_password');
	
	/* ================== Organizations ================== */
	Route::resource(config('laraadmin.adminRoute') . '/organizations', 'LA\OrganizationsController');
	Route::get(config('laraadmin.adminRoute') . '/organization_dt_ajax', 'LA\OrganizationsController@dtajax');

	/* ================== Backups ================== */
	Route::resource(config('laraadmin.adminRoute') . '/backups', 'LA\BackupsController');
	Route::get(config('laraadmin.adminRoute') . '/backup_dt_ajax', 'LA\BackupsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/create_backup_ajax', 'LA\BackupsController@create_backup_ajax');
	Route::get(config('laraadmin.adminRoute') . '/downloadBackup/{id}', 'LA\BackupsController@downloadBackup');

	/* ================== All_establishments ================== */
	Route::resource(config('laraadmin.adminRoute') . '/all_establishments', 'LA\All_establishmentsController');
	Route::get(config('laraadmin.adminRoute') . '/all_establishment_dt_ajax', 'LA\All_establishmentsController@dtajax');
	Route::get(config('laraadmin.adminRoute') . '/saveEst', 'LA\All_establishmentsController@saveAllEst');
	Route::post(config('laraadmin.adminRoute') . '/all_establishments/{id}/{type}', 'LA\All_establishmentsController@index');

	/* ================== Institutes ================== */
	Route::resource(config('laraadmin.adminRoute') . '/institutes', 'LA\InstitutesController');
	Route::get(config('laraadmin.adminRoute') . '/institute_dt_ajax', 'LA\InstitutesController@dtajax');

	/* ================== Senior_posts ================== */
	Route::resource(config('laraadmin.adminRoute') . '/senior_posts', 'LA\Senior_postsController');
	Route::get(config('laraadmin.adminRoute') . '/senior_post_dt_ajax', 'LA\Senior_postsController@dtajax');

	/* ================== Secondary_posts ================== */
	Route::resource(config('laraadmin.adminRoute') . '/secondary_posts', 'LA\Secondary_postsController');
	Route::get(config('laraadmin.adminRoute') . '/secondary_post_dt_ajax', 'LA\Secondary_postsController@dtajax');

	/* ================== Tertiary_posts ================== */
	Route::resource(config('laraadmin.adminRoute') . '/tertiary_posts', 'LA\Tertiary_postsController');
	Route::get(config('laraadmin.adminRoute') . '/tertiary_post_dt_ajax', 'LA\Tertiary_postsController@dtajax');

	/* ================== Primary_posts ================== */
	Route::resource(config('laraadmin.adminRoute') . '/primary_posts', 'LA\Primary_postsController');
	Route::get(config('laraadmin.adminRoute') . '/primary_post_dt_ajax', 'LA\Primary_postsController@dtajax');
	
	/* ================== Reports ================== */
	Route::resource(config('laraadmin.adminRoute') . '/reports', 'LA\ReportsController');
	Route::get(config('laraadmin.adminRoute') . '/report_dt_ajax', 'LA\ReportsController@dtajax');
	Route::get(config('laraadmin.adminRoute') . '/get_ministries','LA\ReportsController@getMinistries');
	Route::get(config('laraadmin.adminRoute') . '/get_rep/{param}','LA\ReportsController@getReplied');
	Route::get(config('laraadmin.adminRoute') . '/all_depts','LA\ReportsController@getAllDepts');
	Route::post(config('laraadmin.adminRoute') . '/minSum','LA\ReportsController@minSum');
	Route::post(config('laraadmin.adminRoute') . '/statMinSum','LA\ReportsController@statMinSum');
	Route::post(config('laraadmin.adminRoute') . '/proviSum','LA\ReportsController@proviSum');
	Route::post(config('laraadmin.adminRoute') . '/getCount','LA\ReportsController@getCount');
	Route::post(config('laraadmin.adminRoute') . '/pdfReprot',array('as'=>'pdfReprot','uses'=>'LA\ReportsController@generateReport'));
});
