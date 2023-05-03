<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Illuminate\Support\Facades\Crypt;

use App\Models\Department;

class DepartmentsController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'name'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Departments', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Departments', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Departments.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Departments');
		
		if(Module::hasAccess($module->id)) {
			return View('la.departments.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new department.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created department in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Departments", "create")) {
		
			$rules = Module::validateRules("Departments", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			if(!Auth::user()->hasRole('SUPER_ADMIN')){
				$instId      = DB::selectOne('select dept from  users u LEFT JOIN employees e ON e.id=u.context_id WHERE u.id=?',[Auth::user()->id]);
				$request->request->add(['parent' =>$instId->dept]);
			}
			$insert_id = Module::insert("Departments", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.departments.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified department.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$id     = Crypt::decrypt($id);
		if(Module::hasAccess("Departments", "view")) {
			
			$department = Department::find($id);
			if(isset($department->id)) {
				$module = Module::get('Departments');
				$module->row = $department;
				
				return view('la.departments.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('department', $department);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("department"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Show the form for editing the specified department.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$id     = Crypt::decrypt($id);
		if(Module::hasAccess("Departments", "edit")) {
			
			$department = Department::find($id);
			if(isset($department->id)) {
				
				$module = Module::get('Departments');
				
				$module->row = $department;
				
				return view('la.departments.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('department', $department);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("department"),
				]);
			}			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified department in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Departments", "edit")) {
			
			$rules = Module::validateRules("Departments", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Departments", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.departments.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified department from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$id     = Crypt::decrypt($id);
		if(Module::hasAccess("Departments", "delete")) {
			Department::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.departments.index');
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}
	
	/**
	 * Datatable Ajax fetch
	 *
	 * @return
	 */
	public function dtajax()
	{
		$instId      = DB::selectOne('select dept from  users u LEFT JOIN employees e ON e.id=u.context_id WHERE u.id=?',[Auth::user()->id]);
		if(Auth::user()->hasRole('SUPER_ADMIN')){
			$values = DB::table('departments')->select($this->listing_cols)->whereNull('deleted_at');			
		}else{
			$values = DB::table('departments')->select($this->listing_cols)->whereNull('deleted_at')->where('parent','=',$instId->dept);			
		}
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Departments');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				$id = Crypt::encrypt($data->data[$i][0]);			
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/departments/'.$id).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Departments", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/departments/'.$id.'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Departments", "delete") && $instId->dept!=$data->data[$i][0]) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.departments.destroy', $id], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
}
