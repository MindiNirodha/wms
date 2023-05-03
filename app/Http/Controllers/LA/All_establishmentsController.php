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
use Carbon\Carbon;
use App\Models\All_establishment;
use Illuminate\Support\Facades\Crypt;

class All_establishmentsController extends Controller
{
	public $show_action = true;
	public $view_col = 'institute_name';
	public $listing_cols = ['id','year', 'senior', 'secondary', 'teritory', 'primary', 'total'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('All_establishments', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('All_establishments', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the All_establishments.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		// dd($request);
		$module = Module::get('All_establishments');
		if(Module::hasAccess($module->id)) {
			//get inserted data to institute
			$instId     = DB::selectOne('select dept from  users u LEFT JOIN employees e ON e.id=u.context_id WHERE u.id=?',[Auth::user()->id]);
			$getData    = DB::select('select * from all_establishments where institute_name='.$instId->dept.' ORDER BY YEAR ASC');
			return View('la.all_establishments.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module,
				'deptdata'=>$insName,
				//'saved' => $getData,
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for creating a new all_establishment.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created all_establishment in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("All_establishments", "create")) {
		
			$rules = Module::validateRules("All_establishments", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			// dd($request);
			if(isset($request->year)){
				for ($i=0; $i <sizeof($request->year) ; $i++) { 
					$insName   = $request->institute_name;
					$Year      = $request->year[$i];
					$Senior    = $request->senior[$i];
					$Secondary = $request->secondary[$i];
					$Teritory  = $request->teritory[$i];
					$Primary   = $request->primary[$i];	
					$Total     = $request->total[$i];
					// $instId    = 
					$values    = array('institute_name'=>$insName,'senior' =>$Senior,'secondary' =>$Secondary,'teritory'=>$Teritory,'primary'=>$Primary,'total'=>$Total,'year'=>$Year,'institute_name'=>$instId->dept,'created_at'=>Carbon::now());
					// dd($values);
					$insertData = DB::table('all_establishments')->insert($values);					
							}
			}
			// dd($request->senior);

			// $insert_id = Module::insert("All_establishments", $request);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.all_establishments.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Display the specified all_establishment.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$id     = Crypt::decrypt($id);
		$module = Module::get('All_establishments');
		if(Module::hasAccess($module->id)) {
			$deptName   = DB::selectOne('select id,name from departments where id='.$id.'');
			//get inserted data to institute
			$getSenior  = DB::select('SELECT SUM(s.2005) as ts1,sum(s.2006) as ts2,sum(s.2007) as ts3,sum(s.2008) as ts4,sum(s.2009) as ts5,sum(s.2010) as ts6,sum(s.2011)as ts7,sum(s.2012)as ts8,sum(s.2013) as ts9,sum(s.2014) as ts10,sum(s.2015) as ts11,sum(s.2016) as ts12,sum(s.2017) as ts13,sum(s.2018) as ts14,sum(s.2019) as ts15,sum(s.2020) as ts16,sum(s.2021) as ts17
										FROM departments d LEFT JOIN senior_posts s ON s.institute_name=d.id WHERE d.id='.$id.' AND s.deleted_at is null');
			$s = array();
			if(count($getSenior)>0){
				foreach ($getSenior as $senior) {
					array_push($s,$senior->ts1);
					array_push($s,$senior->ts2);
					array_push($s,$senior->ts3);
					array_push($s,$senior->ts4);
					array_push($s,$senior->ts5);
					array_push($s,$senior->ts6);
					array_push($s,$senior->ts7);
					array_push($s,$senior->ts8);
					array_push($s,$senior->ts9);
					array_push($s,$senior->ts10);
					array_push($s,$senior->ts11);
					array_push($s,$senior->ts12);
					array_push($s,$senior->ts13);
					array_push($s,$senior->ts14);
					array_push($s,$senior->ts15);
					array_push($s,$senior->ts16);
					array_push($s,$senior->ts17);
				}
			}

			$getSecondary = DB::select('SELECT SUM(se.2005) as tse1,sum(se.2006) as tse2,sum(se.2007) as tse3,sum(se.2008) as tse4,sum(se.2009) as tse5,sum(se.2010) as tse6,sum(se.2011)as tse7,sum(se.2012)as tse8,sum(se.2013) as tse9,sum(se.2014) as tse10,sum(se.2015) as tse11,sum(se.2016) as tse12,sum(se.2017) as tse13,sum(se.2018) as tse14,sum(se.2019) as tse15,sum(se.2020) as tse16,sum(se.2021) as tse17
										FROM departments d LEFT JOIN secondary_posts se ON se.institute_name=d.id WHERE d.id='.$id.' AND se.deleted_at is null');

			$se = array();
			if(count($getSecondary)>0){
				foreach ($getSecondary as $secondary) {
					array_push($se,$secondary->tse1);
					array_push($se,$secondary->tse2);
					array_push($se,$secondary->tse3);
					array_push($se,$secondary->tse4);
					array_push($se,$secondary->tse5);
					array_push($se,$secondary->tse6);
					array_push($se,$secondary->tse7);
					array_push($se,$secondary->tse8);
					array_push($se,$secondary->tse9);
					array_push($se,$secondary->tse10);
					array_push($se,$secondary->tse11);
					array_push($se,$secondary->tse12);
					array_push($se,$secondary->tse13);
					array_push($se,$secondary->tse14);
					array_push($se,$secondary->tse15);
					array_push($se,$secondary->tse16);
					array_push($se,$secondary->tse17);
				}
			}
			$getTeritory = DB::select('SELECT SUM(t.2005) as tt1,sum(t.2006) as tt2,sum(t.2007) as tt3,sum(t.2008) as tt4,sum(t.2009) as tt5,sum(t.2010) as tt6,sum(t.2011)as tt7,sum(t.2012)as tt8,sum(t.2013) as tt9,sum(t.2014) as tt10,sum(t.2015) as tt11,sum(t.2016) as tt12,sum(t.2017) as tt13,sum(t.2018) as tt14,sum(t.2019) as tt15,sum(t.2020) as tt16,sum(t.2021) as tt17
										FROM departments d LEFT JOIN tertiary_posts t ON t.institute_name=d.id WHERE d.id='.$id.' AND t.deleted_at is null');
			$te = array();
			if(count($getTeritory)>0){
				foreach ($getTeritory as $teritory) {
					array_push($te, $teritory->tt1);
					array_push($te, $teritory->tt2);
					array_push($te, $teritory->tt3);
					array_push($te, $teritory->tt4);
					array_push($te, $teritory->tt5);
					array_push($te, $teritory->tt6);
					array_push($te, $teritory->tt7);
					array_push($te, $teritory->tt8);
					array_push($te, $teritory->tt9);
					array_push($te, $teritory->tt10);
					array_push($te, $teritory->tt11);
					array_push($te, $teritory->tt12);
					array_push($te, $teritory->tt13);
					array_push($te, $teritory->tt14);
					array_push($te, $teritory->tt15);
					array_push($te, $teritory->tt16);
					array_push($te, $teritory->tt17);
				}
			}
			$getPrimary = DB::select('SELECT SUM(p.2005) as tp1,sum(p.2006) as tp2,sum(p.2007) as tp3,sum(p.2008) as tp4,sum(p.2009) as tp5,sum(p.2010) as tp6,sum(p.2011)as tp7,sum(p.2012)as tp8,sum(p.2013) as tp9,sum(p.2014) as tp10,sum(p.2015) as tp11,sum(p.2016) as tp12,sum(p.2017) as tp13,sum(p.2018) as tp14,sum(p.2019) as tp15,sum(p.2020) as tp16,sum(p.2021) as tp17
										FROM departments d LEFT JOIN primary_posts p ON p.institute_name=d.id WHERE d.id='.$id.' AND p.deleted_at is null');
			$pr = array();
			if(count($getPrimary)>0){
				foreach ($getPrimary as $primary) {
					array_push($pr, $primary->tp1);
					array_push($pr, $primary->tp2);
					array_push($pr, $primary->tp3);
					array_push($pr, $primary->tp4);
					array_push($pr, $primary->tp5);
					array_push($pr, $primary->tp6);
					array_push($pr, $primary->tp7);
					array_push($pr, $primary->tp8);
					array_push($pr, $primary->tp9);
					array_push($pr, $primary->tp10);
					array_push($pr, $primary->tp11);
					array_push($pr, $primary->tp12);
					array_push($pr, $primary->tp13);
					array_push($pr, $primary->tp14);
					array_push($pr, $primary->tp15);
					array_push($pr, $primary->tp16);
					array_push($pr, $primary->tp17);
				}
			}
			return View('la.all_establishments.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module,
				'deptdata'=>$deptName,
				'senior' => $s,
				'secondary' => $se,
				'teritory' => $te,
				'primary' => $pr,
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Show the form for editing the specified all_establishment.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("All_establishments", "edit")) {			
			$all_establishment = All_establishment::find($id);
			if(isset($all_establishment->id)) {	
				$module = Module::get('All_establishments');
				
				$module->row = $all_establishment;
				
				return view('la.all_establishments.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('all_establishment', $all_establishment);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("all_establishment"),
				]);
			}
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Update the specified all_establishment in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("All_establishments", "edit")) {
			
			$rules = Module::validateRules("All_establishments", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("All_establishments", $request, $id);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.all_establishments.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	/**
	 * Remove the specified all_establishment from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("All_establishments", "delete")) {
			All_establishment::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route(config('laraadmin.adminRoute') . '.all_establishments.index');
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
		$values = DB::table('all_establishments')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('All_establishments');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/all_establishments/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("All_establishments", "edit")) {
					$output .= '<a href="'.url(config('laraadmin.adminRoute') . '/all_establishments/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("All_establishments", "delete")) {
					$output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.all_establishments.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}

	// public function saveAllEst(){
	// 	$Senior    = $_GET['senior'];
	// 	$Secondary = $_GET['secondary'];
	// 	$Teritory  = $_GET['teritory'];
	// 	$Primary   = $_GET['primary'];	
	// 	$Year      = $_GET['year'];
	// 	$Total     = $_GET['total'];
	// 	$instId     = DB::selectOne('select dept from  users u LEFT JOIN employees e ON e.id=u.context_id WHERE u.id=?',[Auth::user()->id]);
	// 	$values     = array('senior' =>$Senior,'secondary' =>$Secondary,'teritory'=>$Teritory,'primary'=>$Primary,'total'=>$Total,'year'=>$Year,'institute_name'=>$instId->dept,'created_at'=>Carbon::now());
	// 	// dd($values);
	// 	$insertData = DB::table('all_establishments')->insert($values);
	// 	if($insertData==1){
	// 		$msg['success'] = true;
	// 	}else{
	// 		$msg['success'] = false;
	// 	}
	// 	return json_encode($msg);
	// }
}
