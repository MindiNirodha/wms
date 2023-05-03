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
use App\Models\Secondary_post;

class Secondary_postsController extends Controller
{
	public $show_action = true;
	public $view_col = 'institute_name';
	public $listing_cols = ['id', 'designation', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021','total'];
	public $listing_cols1 = ['id','institute_name', 'designation', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021','total'];

	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Secondary_posts', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Secondary_posts', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Secondary_posts.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Secondary_posts');
		if(Auth::user()->hasRole('SUPER_ADMIN')){
			$col = $this->listing_cols1;
		}else{
			$col = $this->listing_cols;
		}
		if(Module::hasAccess($module->id)) {
			return View('la.secondary_posts.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $col,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new secondary_post.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created secondary_post in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Secondary_posts", "create")) {
		
			$rules = Module::validateRules("Secondary_posts", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$getStoredData = DB::select('select id from secondary_posts where institute_name='.$request->institute_name.' AND designation='."'$request->designation'".' AND deleted_at is null');
			if(count($getStoredData)>0){
				\Session::flash('duplicate_message', 'Record Already Exists');
				return redirect()->back()->withInput(['duplicate'=>'Record Already Exists']);
			}else{
				$insert_id = Module::insert("Secondary_posts", $request);
				$module = Module::get('Secondary_posts');
				$url       = trim($request->url);
				return redirect($url);
			}
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified secondary_post.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$id     = Crypt::decrypt($id);
		$module = Module::get('Secondary_posts');
		
		if(Module::hasAccess($module->id)) {
			return View('la.secondary_posts.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module,
				'dept_id'=>$id,
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for editing the specified secondary_post.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$id     = Crypt::decrypt($id);
		if(Module::hasAccess("Secondary_posts", "edit")) {			
			$secondary_post = Secondary_post::find($id);
			if(isset($secondary_post->id)) {	
				$module = Module::get('Secondary_posts');
				
				$module->row = $secondary_post;
				
				return view('la.secondary_posts.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('secondary_post', $secondary_post);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("secondary_post"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified secondary_post in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Secondary_posts", "edit")) {
			
			$rules = Module::validateRules("Secondary_posts", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			$getStoredData = DB::select('select id from secondary_posts where institute_name='.$request->institute_name.' AND designation='."'$request->designation'".' AND deleted_at is null');
			$getOldOne     = DB::selectone('select designation from secondary_posts where institute_name='.$request->institute_name.' AND id='.$id.' AND deleted_at is null');
			if(count($getStoredData)>0 && $getOldOne->designation!=$request->designation){
				\Session::flash('duplicate_message', 'Record Already Exists');
				return redirect()->back()->withInput(['duplicate'=>'Record Already Exists']);
			}else{
			$insert_id = Module::updateRow("Secondary_posts", $request, $id);
			$module    = Module::get('Secondary_posts');
			$url       = trim($request->url);
			return redirect($url);
			}			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified secondary_post from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$id     = Crypt::decrypt($id);
		if(Module::hasAccess("Secondary_posts", "delete")) {
			Secondary_post::find($id)->delete();
			$url       = trim($_POST['url']);
			return redirect($url);
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
		$ins    = $_GET['institute_name'];
		if(Auth::user()->hasRole('SUPER_ADMIN')){
			$values   = DB::table('secondary_posts')->select($this->listing_cols1)->whereNull('deleted_at');
			$listCols = $this->listing_cols1;
		}else{
			$values = DB::table('secondary_posts')->select($this->listing_cols)->whereNull('deleted_at')->where('institute_name','=',$ins);
			$listCols = $this->listing_cols;					
		}
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Secondary_posts');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($listCols); $j++) { 
				$col = $listCols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/secondary_posts/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$id= Crypt::encrypt($data->data[$i][0]);		
				$output = '';
				if(Module::hasAccess("Secondary_posts", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/secondary_posts/'.$id.'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Secondary_posts", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.secondary_posts.destroy', $id], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= '<input type="hidden" name="url" value="'.url(config('laraadmin.adminRoute') . '/secondary_posts/'.Crypt::encrypt($ins)).'">';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
}
