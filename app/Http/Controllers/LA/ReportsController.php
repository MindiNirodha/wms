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
use PDF;

use App\Models\Report;

class ReportsController extends Controller
{
	public $show_action = true;
	public $view_col = 'rept_type';
	public $listing_cols = ['id', 'rept_type', 'rpt_min', 'rpt_category'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Reports', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Reports', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Reports.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Reports');
		if(Module::hasAccess($module->id)) {
			return View('la.reports.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
	}

	/**
	 * Store a newly created report in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Reports", "create")) {
		
			$rules = Module::validateRules("Reports", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			if($request->rept_type=='All' && (Auth::user()->hasRole('SUPER_ADMIN') || Auth::user()->hasRole('MIN_USER'))){
				$request->request->remove('rpt_min');
				$instId = DB::selectOne('select dept from  users u LEFT JOIN employees e ON e.id=u.context_id WHERE u.id=?',[Auth::user()->id]);
				$request->request->add(['rpt_min' => $instId->dept]);				
			}			
			$request->request->add(['created_by' => Auth::user()->id]);
		// dd($request);
			$insert_id = Module::insert("Reports", $request);
			$this->generateReport($request->rept_type,$request->rpt_min,$request->rpt_category);
			
			return redirect()->route(config('laraadmin.adminRoute') . '.reports.index');
			
		} else {
			return redirect(config('laraadmin.adminRoute')."/");
		}
	}

	public function getMinistries(){
		//Get relavant dapartments
		if(Auth::user()->hasRole('SUPER_ADMIN')){
			$getDepartments = DB::select('SELECT id,name as text FROM departments WHERE deleted_at IS NULL');
		}else{
			$instId         = DB::selectOne('select dept from  users u LEFT JOIN employees e ON e.id=u.context_id WHERE u.id=?',[Auth::user()->id]);
			$getDepartments = DB::select('SELECT id,name as text FROM departments WHERE (id='.$instId->dept.' OR parent='.$instId->dept.') AND deleted_at is null');
		}
		$getMins = json_encode($getDepartments);
		return $getMins;
	}

	public function generateReport($rptType,$rptMin,$rptCat){
		ini_set("pcre.backtrack_limit","6000000");
		$addedBy     = DB::selectone("SELECT name FROM employees WHERE id=".Auth::user()->id." AND deleted_at is null");
		if($rptType=='All' && Auth::user()->hasRole('SUPER_ADMIN')){
			$result      = $this->createQuery($rptCat);
			// dd($result);
			$pdfData     = array('rpt_data' => $result,'category'=>$rptCat,'prepared'  => $addedBy);
			if($rptCat=='All'){
				$pdf         = PDF::loadView('la.reports.pdf_rpt_all',compact('pdfData'),[],['orientation'=>'L','title'=>'PUBAD-PSRIS']);
			}else{
				$pdf         = PDF::loadView('la.reports.pdf_rpt',compact('pdfData'),[],['orientation'=>'L','title'=>'PUBAD-PSRIS']);
			}
		}else if($rptType=='All' && $rptCat!='All' && !Auth::user()->hasRole('SUPER_ADMIN')){
			$getMin = DB::selectOne('select dept from  users u LEFT JOIN employees e ON e.id=u.context_id WHERE u.id=?',[Auth::user()->id]);
			$result = $this->createQueryAllMin($rptCat,$getMin->dept);
			$pdfData     = array('rpt_data' => $result,'category'=>$rptCat,'prepared'  => $addedBy);
			$pdf         = PDF::loadView('la.reports.pdf_rpt',compact('pdfData'),[],['orientation'=>'L','title'=>'PUBAD-PSRIS']);
		}else if($rptCat=='All' && (Auth::user()->hasRole('SUPER_ADMIN') || Auth::user()->hasRole('MIN_USER'))){
			$result      = $this->createQueryMinAll($rptMin);
			$pdfData     = array('rpt_data' => $result,'category'=>$rptCat,'prepared'  => $addedBy);
			$pdf         = PDF::loadView('la.reports.pdf_rpt_all',compact('pdfData'),[],['orientation'=>'L','title'=>'PUBAD-PSRIS']);
		}
		else{
			$result = $this->createQueryMin($rptCat,$rptMin);
			$pdfData     = array('rpt_data' => $result,'category'=>$rptCat,'prepared'  => $addedBy);
			$pdf         = PDF::loadView('la.reports.pdf_rpt',compact('pdfData'),[],['orientation'=>'L','title'=>'PUBAD-PSRIS']);
		}
		return $pdf->download('Establishment_report.pdf');
	}

	function createQuery($rptCat){
		switch ($rptCat) {
			case 'All':
				$sql['senior']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(s.designation ORDER BY s.id) as sdesig,GROUP_CONCAT(s.2005 ORDER BY s.id)as s2005,GROUP_CONCAT(s.2006 ORDER BY s.id)as s2006,GROUP_CONCAT(s.2007 ORDER BY s.id)as s2007,GROUP_CONCAT(s.2008 ORDER BY s.id)as s2008,
						GROUP_CONCAT(s.2009 ORDER BY s.id)as s2009,GROUP_CONCAT(s.2010 ORDER BY s.id)as s2010,GROUP_CONCAT(s.2011 ORDER BY s.id)as s2011,GROUP_CONCAT(s.2012 ORDER BY s.id)as s2012,GROUP_CONCAT(s.2013 ORDER BY s.id)as s2013,GROUP_CONCAT(s.2014 ORDER BY s.id)as s2014,
						GROUP_CONCAT(s.2015 ORDER BY s.id)as s2015,GROUP_CONCAT(s.2016 ORDER BY s.id)as s2016,GROUP_CONCAT(s.2017 ORDER BY s.id)as s2017,GROUP_CONCAT(s.2018 ORDER BY s.id)as s2018,GROUP_CONCAT(s.2019 ORDER BY s.id)as s2019,GROUP_CONCAT(s.2020 ORDER BY s.id)as s2020,GROUP_CONCAT(s.2021 ORDER BY s.id)as s2021,GROUP_CONCAT(s.total ORDER BY s.id)as total
						FROM departments d
						LEFT JOIN senior_posts s ON s.institute_name=d.id
						WHERE d.deleted_at is null AND s.deleted_at is null AND s.designation IS NOT NULL
						GROUP by d.name ORDER BY d.id");
				$sql['secondary']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(se.designation ORDER BY se.id) as sedesig,GROUP_CONCAT(se.2005 ORDER BY se.id)as se2005,GROUP_CONCAT(se.2006 ORDER BY se.id)as se2006,GROUP_CONCAT(se.2007 ORDER BY se.id)as se2007,GROUP_CONCAT(se.2008 ORDER BY se.id)as se2008,
						GROUP_CONCAT(se.2009 ORDER BY se.id)as se2009,GROUP_CONCAT(se.2010 ORDER BY se.id)as se2010,GROUP_CONCAT(se.2011 ORDER BY se.id)as se2011,GROUP_CONCAT(se.2012 ORDER BY se.id)as se2012,GROUP_CONCAT(se.2013 ORDER BY se.id)as se2013,GROUP_CONCAT(se.2014 ORDER BY se.id)as se2014,
						GROUP_CONCAT(se.2015 ORDER BY se.id)as se2015,GROUP_CONCAT(se.2016 ORDER BY se.id)as se2016,GROUP_CONCAT(se.2017 ORDER BY se.id)as se2017,GROUP_CONCAT(se.2018 ORDER BY se.id)as se2018,GROUP_CONCAT(se.2019 ORDER BY se.id)as se2019,GROUP_CONCAT(se.2020 ORDER BY se.id)as se2020,GROUP_CONCAT(se.2021 ORDER BY se.id)as se2021,GROUP_CONCAT(se.total ORDER BY se.id)as total
						FROM departments d
						LEFT JOIN secondary_posts se ON se.institute_name=d.id
						WHERE d.deleted_at is null AND se.deleted_at is null AND se.designation IS NOT NULL
						GROUP BY d.name ORDER BY d.id");
				$sql['teritory']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(t.designation ORDER BY t.id) as tdesig,GROUP_CONCAT(t.2005 ORDER BY t.id)as t2005,GROUP_CONCAT(t.2006 ORDER BY t.id)as t2006,GROUP_CONCAT(t.2007 ORDER BY t.id)as t2007,GROUP_CONCAT(t.2008 ORDER BY t.id)as t2008,
						GROUP_CONCAT(t.2009 ORDER BY t.id)as t2009,GROUP_CONCAT(t.2010 ORDER BY t.id)as t2010,GROUP_CONCAT(t.2011 ORDER BY t.id)as t2011,GROUP_CONCAT(t.2012 ORDER BY t.id)as t2012,GROUP_CONCAT(t.2013 ORDER BY t.id)as t2013,GROUP_CONCAT(t.2014 ORDER BY t.id)as t2014,
						GROUP_CONCAT(t.2015 ORDER BY t.id)as t2015,GROUP_CONCAT(t.2016 ORDER BY t.id)as t2016,GROUP_CONCAT(t.2017 ORDER BY t.id)as t2017,GROUP_CONCAT(t.2018 ORDER BY t.id)as t2018,GROUP_CONCAT(t.2019 ORDER BY t.id)as t2019,GROUP_CONCAT(t.2020 ORDER BY t.id)as t2020,GROUP_CONCAT(t.2021 ORDER BY t.id)as t2021,GROUP_CONCAT(t.total ORDER BY t.id)as total
						FROM departments d
						LEFT JOIN tertiary_posts t ON t.institute_name=d.id
						WHERE d.deleted_at is null AND t.deleted_at is null AND t.designation IS NOT NULL
						GROUP BY d.name ORDER BY d.id");
				$sql['primary']  =DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(p.designation ORDER BY p.id) as pdesig,GROUP_CONCAT(p.2005 ORDER BY p.id)as p2005,GROUP_CONCAT(p.2006 ORDER BY p.id)as p2006,GROUP_CONCAT(p.2007 ORDER BY p.id)as p2007,GROUP_CONCAT(p.2008 ORDER BY p.id)as p2008,
						GROUP_CONCAT(p.2009 ORDER BY p.id)as p2009,GROUP_CONCAT(p.2010 ORDER BY p.id)as p2010,GROUP_CONCAT(p.2011 ORDER BY p.id)as p2011,GROUP_CONCAT(p.2012 ORDER BY p.id)as p2012,GROUP_CONCAT(p.2013 ORDER BY p.id)as p2013,GROUP_CONCAT(p.2014 ORDER BY p.id)as p2014,
						GROUP_CONCAT(p.2015 ORDER BY p.id)as p2015,GROUP_CONCAT(p.2016 ORDER BY p.id)as p2016,GROUP_CONCAT(p.2017 ORDER BY p.id)as p2017,GROUP_CONCAT(p.2018 ORDER BY p.id)as p2018,GROUP_CONCAT(p.2019 ORDER BY p.id)as p2019,GROUP_CONCAT(p.2020 ORDER BY p.id)as p2020,GROUP_CONCAT(p.2021 ORDER BY p.id)as p2021,GROUP_CONCAT(p.total ORDER BY p.id)as total
						FROM departments d
						LEFT JOIN primary_posts p ON p.institute_name=d.id
						WHERE d.deleted_at is null AND p.deleted_at is null AND p.designation IS NOT NULL
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				break;
				case 'Senior Posts':
				$sql['data']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(s.designation ORDER BY s.id) as desig,GROUP_CONCAT(s.2005 ORDER BY s.id)as 'd2005',GROUP_CONCAT(s.2006 ORDER BY s.id)as 'd2006',GROUP_CONCAT(s.2007 ORDER BY s.id)as 'd2007',GROUP_CONCAT(s.2008 ORDER BY s.id)as 'd2008',
						GROUP_CONCAT(s.2009 ORDER BY s.id)as 'd2009',GROUP_CONCAT(s.2010 ORDER BY s.id)as 'd2010',GROUP_CONCAT(s.2011 ORDER BY s.id)as 'd2011',GROUP_CONCAT(s.2012 ORDER BY s.id)as 'd2012',GROUP_CONCAT(s.2013 ORDER BY s.id)as 'd2013',GROUP_CONCAT(s.2014 ORDER BY s.id)as 'd2014',
						GROUP_CONCAT(s.2015 ORDER BY s.id)as 'd2015',GROUP_CONCAT(s.2016 ORDER BY s.id)as 'd2016',GROUP_CONCAT(s.2017 ORDER BY s.id)as 'd2017',GROUP_CONCAT(s.2018 ORDER BY s.id)as 'd2018',GROUP_CONCAT(s.2019 ORDER BY s.id)as 'd2019',GROUP_CONCAT(s.2020 ORDER BY s.id)as 'd2020',GROUP_CONCAT(s.2021 ORDER BY s.id)as 'd2021',GROUP_CONCAT(s.total ORDER BY s.id)as total
						FROM departments d
						LEFT JOIN senior_posts s ON s.institute_name=d.id
						WHERE d.deleted_at is null AND s.deleted_at is null AND s.designation IS NOT NULL
						GROUP by d.name ORDER BY d.id");
				return $sql;
				break;
				case 'Secondary Posts':
				$sql['data']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(se.designation ORDER BY se.id) as desig,GROUP_CONCAT(se.2005 ORDER BY se.id)as 'd2005',GROUP_CONCAT(se.2006 ORDER BY se.id)as 'd2006',GROUP_CONCAT(se.2007 ORDER BY se.id)as 'd2007',GROUP_CONCAT(se.2008 ORDER BY se.id)as 'd2008',
						GROUP_CONCAT(se.2009 ORDER BY se.id)as 'd2009',GROUP_CONCAT(se.2010 ORDER BY se.id)as 'd2010',GROUP_CONCAT(se.2011 ORDER BY se.id)as 'd2011',GROUP_CONCAT(se.2012 ORDER BY se.id)as 'd2012',GROUP_CONCAT(se.2013 ORDER BY se.id)as 'd2013',GROUP_CONCAT(se.2014 ORDER BY se.id)as 'd2014',
						GROUP_CONCAT(se.2015 ORDER BY se.id)as 'd2015',GROUP_CONCAT(se.2016 ORDER BY se.id)as 'd2016',GROUP_CONCAT(se.2017 ORDER BY se.id)as 'd2017',GROUP_CONCAT(se.2018 ORDER BY se.id)as 'd2018',GROUP_CONCAT(se.2019 ORDER BY se.id)as 'd2019',GROUP_CONCAT(se.2020 ORDER BY se.id)as 'd2020',GROUP_CONCAT(se.2021 ORDER BY se.id)as 'd2021',GROUP_CONCAT(se.total ORDER BY se.id)as total
						FROM departments d
						LEFT JOIN secondary_posts se ON se.institute_name=d.id
						WHERE d.deleted_at is null AND se.deleted_at is null AND se.designation IS NOT NULL
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				break;
				case 'Teritory Posts':
				$sql['data']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(t.designation ORDER BY t.id) as desig,GROUP_CONCAT(t.2005 ORDER BY t.id)as 'd2005',GROUP_CONCAT(t.2006 ORDER BY t.id)as 'd2006',GROUP_CONCAT(t.2007 ORDER BY t.id)as 'd2007',GROUP_CONCAT(t.2008 ORDER BY t.id)as 'd2008',
						GROUP_CONCAT(t.2009 ORDER BY t.id)as 'd2009',GROUP_CONCAT(t.2010 ORDER BY t.id)as 'd2010',GROUP_CONCAT(t.2011 ORDER BY t.id)as 'd2011',GROUP_CONCAT(t.2012 ORDER BY t.id)as 'd2012',GROUP_CONCAT(t.2013 ORDER BY t.id)as 'd2013',GROUP_CONCAT(t.2014 ORDER BY t.id)as 'd2014',
						GROUP_CONCAT(t.2015 ORDER BY t.id)as 'd2015',GROUP_CONCAT(t.2016 ORDER BY t.id)as 'd2016',GROUP_CONCAT(t.2017 ORDER BY t.id)as 'd2017',GROUP_CONCAT(t.2018 ORDER BY t.id)as 'd2018',GROUP_CONCAT(t.2019 ORDER BY t.id)as 'd2019',GROUP_CONCAT(t.2020 ORDER BY t.id)as 'd2020',GROUP_CONCAT(t.2021 ORDER BY t.id)as 'd2021',GROUP_CONCAT(t.total ORDER BY t.id)as total
						FROM departments d
						LEFT JOIN tertiary_posts t ON t.institute_name=d.id
						WHERE d.deleted_at is null AND t.deleted_at is null AND t.designation IS NOT NULL
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				break;
			default:
				$sql['data']  =DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(p.designation ORDER BY p.id) as desig,GROUP_CONCAT(p.2005 ORDER BY p.id)as 'd2005',GROUP_CONCAT(p.2006 ORDER BY p.id)as 'd2006',GROUP_CONCAT(p.2007 ORDER BY p.id)as 'd2007',GROUP_CONCAT(p.2008 ORDER BY p.id)as 'd2008',
						GROUP_CONCAT(p.2009 ORDER BY p.id)as 'd2009',GROUP_CONCAT(p.2010 ORDER BY p.id)as 'd2010',GROUP_CONCAT(p.2011 ORDER BY p.id)as 'd2011',GROUP_CONCAT(p.2012 ORDER BY p.id)as 'd2012',GROUP_CONCAT(p.2013 ORDER BY p.id)as 'd2013',GROUP_CONCAT(p.2014 ORDER BY p.id)as 'd2014',
						GROUP_CONCAT(p.2015 ORDER BY p.id)as 'd2015',GROUP_CONCAT(p.2016 ORDER BY p.id)as 'd2016',GROUP_CONCAT(p.2017 ORDER BY p.id)as 'd2017',GROUP_CONCAT(p.2018 ORDER BY p.id)as 'd2018',GROUP_CONCAT(p.2019 ORDER BY p.id)as 'd2019',GROUP_CONCAT(p.2020 ORDER BY p.id)as 'd2020',GROUP_CONCAT(p.2021 ORDER BY p.id)as 'd2021',GROUP_CONCAT(p.total ORDER BY p.id)as total
						FROM departments d
						LEFT JOIN primary_posts p ON p.institute_name=d.id
						WHERE d.deleted_at is null AND p.deleted_at is null AND p.designation IS NOT NULL
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				break;
		}
		// dd($sql);
	}

	function createQueryMin($rptCat,$rptMin){
		switch ($rptCat) {
			case 'All':
				$sql['senior']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(s.designation ORDER BY s.id) as sdesig,GROUP_CONCAT(s.2005 ORDER BY s.id)as s2005,GROUP_CONCAT(s.2006 ORDER BY s.id)as s2006,GROUP_CONCAT(s.2007 ORDER BY s.id)as s2007,GROUP_CONCAT(s.2008 ORDER BY s.id)as s2008,
						GROUP_CONCAT(s.2009 ORDER BY s.id)as s2009,GROUP_CONCAT(s.2010 ORDER BY s.id)as s2010,GROUP_CONCAT(s.2011 ORDER BY s.id)as s2011,GROUP_CONCAT(s.2012 ORDER BY s.id)as s2012,GROUP_CONCAT(s.2013 ORDER BY s.id)as s2013,GROUP_CONCAT(s.2014 ORDER BY s.id)as s2014,
						GROUP_CONCAT(s.2015 ORDER BY s.id)as s2015,GROUP_CONCAT(s.2016 ORDER BY s.id)as s2016,GROUP_CONCAT(s.2017 ORDER BY s.id)as s2017,GROUP_CONCAT(s.2018 ORDER BY s.id)as s2018,GROUP_CONCAT(s.2019 ORDER BY s.id)as s2019,GROUP_CONCAT(s.2020 ORDER BY s.id)as s2020,GROUP_CONCAT(s.2021 ORDER BY s.id)as s2021
						FROM departments d
						LEFT JOIN senior_posts s ON s.institute_name=d.id
						WHERE d.deleted_at is null AND s.deleted_at is null AND s.designation IS NOT NULL AND (d.id=".$rptMin." OR d.parent=".$rptMin.") 
						GROUP by d.name ORDER BY d.id");
				$sql['secondary']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(se.designation ORDER BY se.id) as sedesig,GROUP_CONCAT(se.2005 ORDER BY se.id)as se2005,GROUP_CONCAT(se.2006 ORDER BY se.id)as se2006,GROUP_CONCAT(se.2007 ORDER BY se.id)as se2007,GROUP_CONCAT(se.2008 ORDER BY se.id)as se2008,
						GROUP_CONCAT(se.2009 ORDER BY se.id)as se2009,GROUP_CONCAT(se.2010 ORDER BY se.id)as se2010,GROUP_CONCAT(se.2011 ORDER BY se.id)as se2011,GROUP_CONCAT(se.2012 ORDER BY se.id)as se2012,GROUP_CONCAT(se.2013 ORDER BY se.id)as se2013,GROUP_CONCAT(se.2014 ORDER BY se.id)as se2014,
						GROUP_CONCAT(se.2015 ORDER BY se.id)as se2015,GROUP_CONCAT(se.2016 ORDER BY se.id)as se2016,GROUP_CONCAT(se.2017 ORDER BY se.id)as se2017,GROUP_CONCAT(se.2018 ORDER BY se.id)as se2018,GROUP_CONCAT(se.2019 ORDER BY se.id)as se2019,GROUP_CONCAT(se.2020 ORDER BY se.id)as se2020,GROUP_CONCAT(se.2021 ORDER BY se.id)as se2021
						FROM departments d
						LEFT JOIN secondary_posts se ON se.institute_name=d.id
						WHERE d.deleted_at is null AND se.deleted_at is null AND se.designation IS NOT NULL AND (d.id=".$rptMin." OR d.parent=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
				$sql['teritory']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(t.designation ORDER BY t.id) as tdesig,GROUP_CONCAT(t.2005 ORDER BY t.id)as t2005,GROUP_CONCAT(t.2006 ORDER BY t.id)as t2006,GROUP_CONCAT(t.2007 ORDER BY t.id)as t2007,GROUP_CONCAT(t.2008 ORDER BY t.id)as t2008,
						GROUP_CONCAT(t.2009 ORDER BY t.id)as t2009,GROUP_CONCAT(t.2010 ORDER BY t.id)as t2010,GROUP_CONCAT(t.2011 ORDER BY t.id)as t2011,GROUP_CONCAT(t.2012 ORDER BY t.id)as t2012,GROUP_CONCAT(t.2013 ORDER BY t.id)as t2013,GROUP_CONCAT(t.2014 ORDER BY t.id)as t2014,
						GROUP_CONCAT(t.2015 ORDER BY t.id)as t2015,GROUP_CONCAT(t.2016 ORDER BY t.id)as t2016,GROUP_CONCAT(t.2017 ORDER BY t.id)as t2017,GROUP_CONCAT(t.2018 ORDER BY t.id)as t2018,GROUP_CONCAT(t.2019 ORDER BY t.id)as t2019,GROUP_CONCAT(t.2020 ORDER BY t.id)as t2020,GROUP_CONCAT(t.2021 ORDER BY t.id)as t2021
						FROM departments d
						LEFT JOIN tertiary_posts t ON t.institute_name=d.id
						WHERE d.deleted_at is null AND t.deleted_at is null AND t.designation IS NOT NULL AND (d.id=".$rptMin." OR d.parent=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
				$sql['primary']  =DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(p.designation ORDER BY p.id) as pdesig,GROUP_CONCAT(p.2005 ORDER BY p.id)as p2005,GROUP_CONCAT(p.2006 ORDER BY p.id)as p2006,GROUP_CONCAT(p.2007 ORDER BY p.id)as p2007,GROUP_CONCAT(p.2008 ORDER BY p.id)as p2008,
						GROUP_CONCAT(p.2009 ORDER BY p.id)as p2009,GROUP_CONCAT(p.2010 ORDER BY p.id)as p2010,GROUP_CONCAT(p.2011 ORDER BY p.id)as p2011,GROUP_CONCAT(p.2012 ORDER BY p.id)as p2012,GROUP_CONCAT(p.2013 ORDER BY p.id)as p2013,GROUP_CONCAT(p.2014 ORDER BY p.id)as p2014,
						GROUP_CONCAT(p.2015 ORDER BY p.id)as p2015,GROUP_CONCAT(p.2016 ORDER BY p.id)as p2016,GROUP_CONCAT(p.2017 ORDER BY p.id)as p2017,GROUP_CONCAT(p.2018 ORDER BY p.id)as p2018,GROUP_CONCAT(p.2019 ORDER BY p.id)as p2019,GROUP_CONCAT(p.2020 ORDER BY p.id)as p2020,GROUP_CONCAT(p.2021 ORDER BY p.id)as p2021
						FROM departments d
						LEFT JOIN primary_posts p ON p.institute_name=d.id
						WHERE d.deleted_at is null AND p.deleted_at is null AND p.designation IS NOT NULL AND (d.id=".$rptMin." OR d.parent=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				break;
				case 'Senior Posts':
				$sql['data']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(s.designation ORDER BY s.id) as desig,GROUP_CONCAT(s.2005 ORDER BY s.id)as 'd2005',GROUP_CONCAT(s.2006 ORDER BY s.id)as 'd2006',GROUP_CONCAT(s.2007 ORDER BY s.id)as 'd2007',GROUP_CONCAT(s.2008 ORDER BY s.id)as 'd2008',
						GROUP_CONCAT(s.2009 ORDER BY s.id)as 'd2009',GROUP_CONCAT(s.2010 ORDER BY s.id)as 'd2010',GROUP_CONCAT(s.2011 ORDER BY s.id)as 'd2011',GROUP_CONCAT(s.2012 ORDER BY s.id)as 'd2012',GROUP_CONCAT(s.2013 ORDER BY s.id)as 'd2013',GROUP_CONCAT(s.2014 ORDER BY s.id)as 'd2014',
						GROUP_CONCAT(s.2015 ORDER BY s.id)as 'd2015',GROUP_CONCAT(s.2016 ORDER BY s.id)as 'd2016',GROUP_CONCAT(s.2017 ORDER BY s.id)as 'd2017',GROUP_CONCAT(s.2018 ORDER BY s.id)as 'd2018',GROUP_CONCAT(s.2019 ORDER BY s.id)as 'd2019',GROUP_CONCAT(s.2020 ORDER BY s.id)as 'd2020',GROUP_CONCAT(s.2021 ORDER BY s.id)as 'd2021',GROUP_CONCAT(s.total ORDER BY s.id)as total
						FROM departments d
						LEFT JOIN senior_posts s ON s.institute_name=d.id
						WHERE d.deleted_at is null AND s.deleted_at is null AND s.designation IS NOT NULL AND (d.id=".$rptMin.")
						GROUP by d.name ORDER BY d.id");
				return $sql;
				break;
				case 'Secondary Posts':
				$sql['data']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(se.designation ORDER BY se.id) as desig,GROUP_CONCAT(se.2005 ORDER BY se.id)as 'd2005',GROUP_CONCAT(se.2006 ORDER BY se.id)as 'd2006',GROUP_CONCAT(se.2007 ORDER BY se.id)as 'd2007',GROUP_CONCAT(se.2008 ORDER BY se.id)as 'd2008',
						GROUP_CONCAT(se.2009 ORDER BY se.id)as 'd2009',GROUP_CONCAT(se.2010 ORDER BY se.id)as 'd2010',GROUP_CONCAT(se.2011 ORDER BY se.id)as 'd2011',GROUP_CONCAT(se.2012 ORDER BY se.id)as 'd2012',GROUP_CONCAT(se.2013 ORDER BY se.id)as 'd2013',GROUP_CONCAT(se.2014 ORDER BY se.id)as 'd2014',
						GROUP_CONCAT(se.2015 ORDER BY se.id)as 'd2015',GROUP_CONCAT(se.2016 ORDER BY se.id)as 'd2016',GROUP_CONCAT(se.2017 ORDER BY se.id)as 'd2017',GROUP_CONCAT(se.2018 ORDER BY se.id)as 'd2018',GROUP_CONCAT(se.2019 ORDER BY se.id)as 'd2019',GROUP_CONCAT(se.2020 ORDER BY se.id)as 'd2020',GROUP_CONCAT(se.2021 ORDER BY se.id)as 'd2021',GROUP_CONCAT(se.total ORDER BY se.id)as total
						FROM departments d
						LEFT JOIN secondary_posts se ON se.institute_name=d.id
						WHERE d.deleted_at is null AND se.deleted_at is null AND se.designation IS NOT NULL AND (d.id=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				beak;
				case 'Teritory Posts':
				$sql['data']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(t.designation ORDER BY t.id) as desig,GROUP_CONCAT(t.2005 ORDER BY t.id)as 'd2005',GROUP_CONCAT(t.2006 ORDER BY t.id)as 'd2006',GROUP_CONCAT(t.2007 ORDER BY t.id)as 'd2007',GROUP_CONCAT(t.2008 ORDER BY t.id)as 'd2008',
						GROUP_CONCAT(t.2009 ORDER BY t.id)as 'd2009',GROUP_CONCAT(t.2010 ORDER BY t.id)as 'd2010',GROUP_CONCAT(t.2011 ORDER BY t.id)as 'd2011',GROUP_CONCAT(t.2012 ORDER BY t.id)as 'd2012',GROUP_CONCAT(t.2013 ORDER BY t.id)as 'd2013',GROUP_CONCAT(t.2014 ORDER BY t.id)as 'd2014',
						GROUP_CONCAT(t.2015 ORDER BY t.id)as 'd2015',GROUP_CONCAT(t.2016 ORDER BY t.id)as 'd2016',GROUP_CONCAT(t.2017 ORDER BY t.id)as 'd2017',GROUP_CONCAT(t.2018 ORDER BY t.id)as 'd2018',GROUP_CONCAT(t.2019 ORDER BY t.id)as 'd2019',GROUP_CONCAT(t.2020 ORDER BY t.id)as 'd2020',GROUP_CONCAT(t.2021 ORDER BY t.id)as 'd2021',GROUP_CONCAT(t.total ORDER BY t.id)as total
						FROM departments d
						LEFT JOIN tertiary_posts t ON t.institute_name=d.id
						WHERE d.deleted_at is null AND t.deleted_at is null AND t.designation IS NOT NULL AND (d.id=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				break;
			default:
				$sql['data']  =DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(p.designation ORDER BY p.id) as desig,GROUP_CONCAT(p.2005 ORDER BY p.id)as 'd2005',GROUP_CONCAT(p.2006 ORDER BY p.id)as 'd2006',GROUP_CONCAT(p.2007 ORDER BY p.id)as 'd2007',GROUP_CONCAT(p.2008 ORDER BY p.id)as 'd2008',
						GROUP_CONCAT(p.2009 ORDER BY p.id)as 'd2009',GROUP_CONCAT(p.2010 ORDER BY p.id)as 'd2010',GROUP_CONCAT(p.2011 ORDER BY p.id)as 'd2011',GROUP_CONCAT(p.2012 ORDER BY p.id)as 'd2012',GROUP_CONCAT(p.2013 ORDER BY p.id)as 'd2013',GROUP_CONCAT(p.2014 ORDER BY p.id)as 'd2014',
						GROUP_CONCAT(p.2015 ORDER BY p.id)as 'd2015',GROUP_CONCAT(p.2016 ORDER BY p.id)as 'd2016',GROUP_CONCAT(p.2017 ORDER BY p.id)as 'd2017',GROUP_CONCAT(p.2018 ORDER BY p.id)as 'd2018',GROUP_CONCAT(p.2019 ORDER BY p.id)as 'd2019',GROUP_CONCAT(p.2020 ORDER BY p.id)as 'd2020',GROUP_CONCAT(p.2021 ORDER BY p.id)as 'd2021',GROUP_CONCAT(p.total ORDER BY p.id)as total
						FROM departments d
						LEFT JOIN primary_posts p ON p.institute_name=d.id
						WHERE d.deleted_at is null AND p.deleted_at is null AND p.designation IS NOT NULL AND (d.id=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				break;
		}
		// dd($sql);
	}

	function createQueryAllMin($rptCat,$rptMin){
		switch ($rptCat) {
			case 'Senior Posts':
				$sql['data']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(s.designation ORDER BY s.id) as desig,GROUP_CONCAT(s.2005 ORDER BY s.id)as 'd2005',GROUP_CONCAT(s.2006 ORDER BY s.id)as 'd2006',GROUP_CONCAT(s.2007 ORDER BY s.id)as 'd2007',GROUP_CONCAT(s.2008 ORDER BY s.id)as 'd2008',
						GROUP_CONCAT(s.2009 ORDER BY s.id)as 'd2009',GROUP_CONCAT(s.2010 ORDER BY s.id)as 'd2010',GROUP_CONCAT(s.2011 ORDER BY s.id)as 'd2011',GROUP_CONCAT(s.2012 ORDER BY s.id)as 'd2012',GROUP_CONCAT(s.2013 ORDER BY s.id)as 'd2013',GROUP_CONCAT(s.2014 ORDER BY s.id)as 'd2014',
						GROUP_CONCAT(s.2015 ORDER BY s.id)as 'd2015',GROUP_CONCAT(s.2016 ORDER BY s.id)as 'd2016',GROUP_CONCAT(s.2017 ORDER BY s.id)as 'd2017',GROUP_CONCAT(s.2018 ORDER BY s.id)as 'd2018',GROUP_CONCAT(s.2019 ORDER BY s.id)as 'd2019',GROUP_CONCAT(s.2020 ORDER BY s.id)as 'd2020',GROUP_CONCAT(s.2021 ORDER BY s.id)as 'd2021',GROUP_CONCAT(s.total ORDER BY s.id)as total
						FROM departments d
						LEFT JOIN senior_posts s ON s.institute_name=d.id
						WHERE d.deleted_at is null AND s.deleted_at is null AND s.designation IS NOT NULL AND (d.id=".$rptMin." OR d.parent=".$rptMin.")
						GROUP by d.name ORDER BY d.id");
				return $sql;
				break;
				case 'Secondary Posts':
				$sql['data']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(se.designation ORDER BY se.id) as desig,GROUP_CONCAT(se.2005 ORDER BY se.id)as 'd2005',GROUP_CONCAT(se.2006 ORDER BY se.id)as 'd2006',GROUP_CONCAT(se.2007 ORDER BY se.id)as 'd2007',GROUP_CONCAT(se.2008 ORDER BY se.id)as 'd2008',
						GROUP_CONCAT(se.2009 ORDER BY se.id)as 'd2009',GROUP_CONCAT(se.2010 ORDER BY se.id)as 'd2010',GROUP_CONCAT(se.2011 ORDER BY se.id)as 'd2011',GROUP_CONCAT(se.2012 ORDER BY se.id)as 'd2012',GROUP_CONCAT(se.2013 ORDER BY se.id)as 'd2013',GROUP_CONCAT(se.2014 ORDER BY se.id)as 'd2014',
						GROUP_CONCAT(se.2015 ORDER BY se.id)as 'd2015',GROUP_CONCAT(se.2016 ORDER BY se.id)as 'd2016',GROUP_CONCAT(se.2017 ORDER BY se.id)as 'd2017',GROUP_CONCAT(se.2018 ORDER BY se.id)as 'd2018',GROUP_CONCAT(se.2019 ORDER BY se.id)as 'd2019',GROUP_CONCAT(se.2020 ORDER BY se.id)as 'd2020',GROUP_CONCAT(se.2021 ORDER BY se.id)as 'd2021',GROUP_CONCAT(se.total ORDER BY se.id)as total
						FROM departments d
						LEFT JOIN secondary_posts se ON se.institute_name=d.id
						WHERE d.deleted_at is null AND se.deleted_at is null AND se.designation IS NOT NULL AND (d.id=".$rptMin." OR d.parent=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				beak;
				case 'Teritory Posts':
				$sql['data']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(t.designation ORDER BY t.id) as desig,GROUP_CONCAT(t.2005 ORDER BY t.id)as 'd2005',GROUP_CONCAT(t.2006 ORDER BY t.id)as 'd2006',GROUP_CONCAT(t.2007 ORDER BY t.id)as 'd2007',GROUP_CONCAT(t.2008 ORDER BY t.id)as 'd2008',
						GROUP_CONCAT(t.2009 ORDER BY t.id)as 'd2009',GROUP_CONCAT(t.2010 ORDER BY t.id)as 'd2010',GROUP_CONCAT(t.2011 ORDER BY t.id)as 'd2011',GROUP_CONCAT(t.2012 ORDER BY t.id)as 'd2012',GROUP_CONCAT(t.2013 ORDER BY t.id)as 'd2013',GROUP_CONCAT(t.2014 ORDER BY t.id)as 'd2014',
						GROUP_CONCAT(t.2015 ORDER BY t.id)as 'd2015',GROUP_CONCAT(t.2016 ORDER BY t.id)as 'd2016',GROUP_CONCAT(t.2017 ORDER BY t.id)as 'd2017',GROUP_CONCAT(t.2018 ORDER BY t.id)as 'd2018',GROUP_CONCAT(t.2019 ORDER BY t.id)as 'd2019',GROUP_CONCAT(t.2020 ORDER BY t.id)as 'd2020',GROUP_CONCAT(t.2021 ORDER BY t.id)as 'd2021',GROUP_CONCAT(t.total ORDER BY t.id)as total
						FROM departments d
						LEFT JOIN tertiary_posts t ON t.institute_name=d.id
						WHERE d.deleted_at is null AND t.deleted_at is null AND t.designation IS NOT NULL AND (d.id=".$rptMin." OR d.parent=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				break;
			default:
				$sql['data']  =DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(p.designation ORDER BY p.id) as desig,GROUP_CONCAT(p.2005 ORDER BY p.id)as 'd2005',GROUP_CONCAT(p.2006 ORDER BY p.id)as 'd2006',GROUP_CONCAT(p.2007 ORDER BY p.id)as 'd2007',GROUP_CONCAT(p.2008 ORDER BY p.id)as 'd2008',
						GROUP_CONCAT(p.2009 ORDER BY p.id)as 'd2009',GROUP_CONCAT(p.2010 ORDER BY p.id)as 'd2010',GROUP_CONCAT(p.2011 ORDER BY p.id)as 'd2011',GROUP_CONCAT(p.2012 ORDER BY p.id)as 'd2012',GROUP_CONCAT(p.2013 ORDER BY p.id)as 'd2013',GROUP_CONCAT(p.2014 ORDER BY p.id)as 'd2014',
						GROUP_CONCAT(p.2015 ORDER BY p.id)as 'd2015',GROUP_CONCAT(p.2016 ORDER BY p.id)as 'd2016',GROUP_CONCAT(p.2017 ORDER BY p.id)as 'd2017',GROUP_CONCAT(p.2018 ORDER BY p.id)as 'd2018',GROUP_CONCAT(p.2019 ORDER BY p.id)as 'd2019',GROUP_CONCAT(p.2020 ORDER BY p.id)as 'd2020',GROUP_CONCAT(p.2021 ORDER BY p.id)as 'd2021',GROUP_CONCAT(p.total ORDER BY p.id)as total
						FROM departments d
						LEFT JOIN primary_posts p ON p.institute_name=d.id
						WHERE d.deleted_at is null AND p.deleted_at is null AND p.designation IS NOT NULL AND (d.id=".$rptMin." OR d.parent=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
				return $sql;
				break;
		}
	}

	function createQueryMinAll($rptMin){
		$sql['senior']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(s.designation ORDER BY s.id) as sdesig,GROUP_CONCAT(s.2005 ORDER BY s.id)as s2005,GROUP_CONCAT(s.2006 ORDER BY s.id)as s2006,GROUP_CONCAT(s.2007 ORDER BY s.id)as s2007,GROUP_CONCAT(s.2008 ORDER BY s.id)as s2008,
						GROUP_CONCAT(s.2009 ORDER BY s.id)as s2009,GROUP_CONCAT(s.2010 ORDER BY s.id)as s2010,GROUP_CONCAT(s.2011 ORDER BY s.id)as s2011,GROUP_CONCAT(s.2012 ORDER BY s.id)as s2012,GROUP_CONCAT(s.2013 ORDER BY s.id)as s2013,GROUP_CONCAT(s.2014 ORDER BY s.id)as s2014,
						GROUP_CONCAT(s.2015 ORDER BY s.id)as s2015,GROUP_CONCAT(s.2016 ORDER BY s.id)as s2016,GROUP_CONCAT(s.2017 ORDER BY s.id)as s2017,GROUP_CONCAT(s.2018 ORDER BY s.id)as s2018,GROUP_CONCAT(s.2019 ORDER BY s.id)as s2019,GROUP_CONCAT(s.2020 ORDER BY s.id)as s2020,GROUP_CONCAT(s.2021 ORDER BY s.id)as s2021,GROUP_CONCAT(s.total ORDER BY s.id)as total
						FROM departments d
						LEFT JOIN senior_posts s ON s.institute_name=d.id
						WHERE d.deleted_at is null AND s.deleted_at is null AND s.designation IS NOT NULL AND (d.id=".$rptMin.") 
						GROUP by d.name ORDER BY d.id");
		$sql['secondary']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(se.designation ORDER BY se.id) as sedesig,GROUP_CONCAT(se.2005 ORDER BY se.id)as se2005,GROUP_CONCAT(se.2006 ORDER BY se.id)as se2006,GROUP_CONCAT(se.2007 ORDER BY se.id)as se2007,GROUP_CONCAT(se.2008 ORDER BY se.id)as se2008,
						GROUP_CONCAT(se.2009 ORDER BY se.id)as se2009,GROUP_CONCAT(se.2010 ORDER BY se.id)as se2010,GROUP_CONCAT(se.2011 ORDER BY se.id)as se2011,GROUP_CONCAT(se.2012 ORDER BY se.id)as se2012,GROUP_CONCAT(se.2013 ORDER BY se.id)as se2013,GROUP_CONCAT(se.2014 ORDER BY se.id)as se2014,
						GROUP_CONCAT(se.2015 ORDER BY se.id)as se2015,GROUP_CONCAT(se.2016 ORDER BY se.id)as se2016,GROUP_CONCAT(se.2017 ORDER BY se.id)as se2017,GROUP_CONCAT(se.2018 ORDER BY se.id)as se2018,GROUP_CONCAT(se.2019 ORDER BY se.id)as se2019,GROUP_CONCAT(se.2020 ORDER BY se.id)as se2020,GROUP_CONCAT(se.2021 ORDER BY se.id)as se2021,GROUP_CONCAT(se.total ORDER BY se.id)as total
						FROM departments d
						LEFT JOIN secondary_posts se ON se.institute_name=d.id
						WHERE d.deleted_at is null AND se.deleted_at is null AND se.designation IS NOT NULL AND (d.id=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
		$sql['teritory']  = DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(t.designation ORDER BY t.id) as tdesig,GROUP_CONCAT(t.2005 ORDER BY t.id)as t2005,GROUP_CONCAT(t.2006 ORDER BY t.id)as t2006,GROUP_CONCAT(t.2007 ORDER BY t.id)as t2007,GROUP_CONCAT(t.2008 ORDER BY t.id)as t2008,
						GROUP_CONCAT(t.2009 ORDER BY t.id)as t2009,GROUP_CONCAT(t.2010 ORDER BY t.id)as t2010,GROUP_CONCAT(t.2011 ORDER BY t.id)as t2011,GROUP_CONCAT(t.2012 ORDER BY t.id)as t2012,GROUP_CONCAT(t.2013 ORDER BY t.id)as t2013,GROUP_CONCAT(t.2014 ORDER BY t.id)as t2014,
						GROUP_CONCAT(t.2015 ORDER BY t.id)as t2015,GROUP_CONCAT(t.2016 ORDER BY t.id)as t2016,GROUP_CONCAT(t.2017 ORDER BY t.id)as t2017,GROUP_CONCAT(t.2018 ORDER BY t.id)as t2018,GROUP_CONCAT(t.2019 ORDER BY t.id)as t2019,GROUP_CONCAT(t.2020 ORDER BY t.id)as t2020,GROUP_CONCAT(t.2021 ORDER BY t.id)as t2021,GROUP_CONCAT(t.total ORDER BY t.id)as total
						FROM departments d
						LEFT JOIN tertiary_posts t ON t.institute_name=d.id
						WHERE d.deleted_at is null AND t.deleted_at is null AND t.designation IS NOT NULL AND (d.id=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
		$sql['primary']  =DB::select("SELECT DISTINCT(d.id),d.name,
						GROUP_CONCAT(p.designation ORDER BY p.id) as pdesig,GROUP_CONCAT(p.2005 ORDER BY p.id)as p2005,GROUP_CONCAT(p.2006 ORDER BY p.id)as p2006,GROUP_CONCAT(p.2007 ORDER BY p.id)as p2007,GROUP_CONCAT(p.2008 ORDER BY p.id)as p2008,
						GROUP_CONCAT(p.2009 ORDER BY p.id)as p2009,GROUP_CONCAT(p.2010 ORDER BY p.id)as p2010,GROUP_CONCAT(p.2011 ORDER BY p.id)as p2011,GROUP_CONCAT(p.2012 ORDER BY p.id)as p2012,GROUP_CONCAT(p.2013 ORDER BY p.id)as p2013,GROUP_CONCAT(p.2014 ORDER BY p.id)as p2014,
						GROUP_CONCAT(p.2015 ORDER BY p.id)as p2015,GROUP_CONCAT(p.2016 ORDER BY p.id)as p2016,GROUP_CONCAT(p.2017 ORDER BY p.id)as p2017,GROUP_CONCAT(p.2018 ORDER BY p.id)as p2018,GROUP_CONCAT(p.2019 ORDER BY p.id)as p2019,GROUP_CONCAT(p.2020 ORDER BY p.id)as p2020,GROUP_CONCAT(p.2021 ORDER BY p.id)as p2021,GROUP_CONCAT(p.total ORDER BY p.id)as total
						FROM departments d
						LEFT JOIN primary_posts p ON p.institute_name=d.id
						WHERE d.deleted_at is null AND p.deleted_at is null AND p.designation IS NOT NULL AND (d.id=".$rptMin.")
						GROUP BY d.name ORDER BY d.id");
		return $sql;
	}

	public function getReplied($param){
		$ministry      = DB::select('SELECT d.id,d.name as dept,e.name,e.designation,e.mobile,d.status as st,
			           		(SELECT COUNT(*) FROM  senior_posts s WHERE s.institute_name = d.id AND s.deleted_at is null) +
			           		(SELECT COUNT(*) FROM secondary_posts se WHERE se.institute_name = d.id AND se.deleted_at is null)+
			           		(SELECT COUNT(*) FROM tertiary_posts t WHERE t.institute_name = d.id AND t.deleted_at is null) +
			           		(SELECT COUNT(*) FROM primary_posts p WHERE p.institute_name = d.id AND p.deleted_at is null)as tc
							FROM  departments d 
							LEFT JOIN employees e ON e.dept=d.id
							WHERE d.deleted_at is null AND d.id!=1 AND d.parent=1 AND e.deleted_at is null AND d.dept_type="Ministry"'); 
		$stateMin     = DB::select('SELECT d.id,d.name as dept,e.name,e.designation,e.mobile,d.status as st,
			           		(SELECT COUNT(*) FROM  senior_posts s WHERE s.institute_name = d.id AND s.deleted_at is null) +
			           		(SELECT COUNT(*) FROM secondary_posts se WHERE se.institute_name = d.id AND se.deleted_at is null)+
			           		(SELECT COUNT(*) FROM tertiary_posts t WHERE t.institute_name = d.id AND t.deleted_at is null) +
			           		(SELECT COUNT(*) FROM primary_posts p WHERE p.institute_name = d.id AND p.deleted_at is null)as tc
							FROM  departments d 
							LEFT JOIN employees e ON e.dept=d.id
							WHERE d.deleted_at is null AND d.id!=1 AND d.parent=1 AND e.deleted_at is null AND d.dept_type="State Ministry"');
		$provincial   = DB::select('SELECT d.id,d.name as dept,e.name,e.designation,e.mobile,d.status as st,
			           		(SELECT COUNT(*) FROM  senior_posts s WHERE s.institute_name = d.id AND s.deleted_at is null) +
			           		(SELECT COUNT(*) FROM secondary_posts se WHERE se.institute_name = d.id AND se.deleted_at is null)+
			           		(SELECT COUNT(*) FROM tertiary_posts t WHERE t.institute_name = d.id AND t.deleted_at is null) +
			           		(SELECT COUNT(*) FROM primary_posts p WHERE p.institute_name = d.id AND p.deleted_at is null)as tc
							FROM  departments d 
							LEFT JOIN employees e ON e.dept=d.id
							WHERE d.deleted_at is null AND d.id!=1 AND d.parent=1 AND e.deleted_at is null AND d.dept_type="Provincial Council"');
		$minData      = array_merge($ministry,$stateMin,$provincial);
		
		if($param=='rep'){
			foreach ($minData as $key => $value) {
                if($value->tc==0 && ($value->st=='No Reply')){
                    unset($minData[$key]);
                }
            }

		}else{
			foreach ($minData as $key => $value) {
                if($value->st!='No Reply'){
                    unset($minData[$key]);
                }
            }
		}
	    return View('la.reports.rep', [
				'data'=>$minData,
				'param'=>$param
		]);
	}

	public function getAllDepts(){
		$ministries          = DB::select('SELECT t2.name as lev2,GROUP_CONCAT(t3.name order by t3.id) as sub
											FROM departments AS t1
											LEFT JOIN departments AS t2 ON t2.parent = t1.id
											LEFT JOIN departments As t3 ON t3.parent = t2.id
											WHERE t1.id =1 AND t1.deleted_at is null AND t2.id!=1 AND t2.deleted_at is null AND t2.dept_type="Ministry" AND t3.deleted_at is null
											GROUP BY t2.id');
		$state_ministries    = DB::select('SELECT t2.name as lev2,GROUP_CONCAT(t3.name order by t3.id) as sub
											FROM departments AS t1
											LEFT JOIN departments AS t2 ON t2.parent = t1.id
											LEFT JOIN departments As t3 ON t3.parent = t2.id
											WHERE t1.id =1 AND t1.deleted_at is null AND t2.id!=1 AND t2.deleted_at is null AND t2.dept_type="State Ministry" AND t3.deleted_at is null
											GROUP BY t2.id');
		$provincial_councils = DB::select('SELECT t2.name as lev2,GROUP_CONCAT(t3.name order by t3.id) as sub
											FROM departments AS t1
											LEFT JOIN departments AS t2 ON t2.parent = t1.id
											LEFT JOIN departments As t3 ON t3.parent = t2.id
											WHERE t1.id =1 AND t1.deleted_at is null AND t2.id!=1 AND t2.deleted_at is null AND t2.dept_type="Provincial Council" AND t3.deleted_at is null
											GROUP BY t2.id');
		return View('la.reports.show_all_depts', [
				'min' =>$ministries,
				'stat'=>$state_ministries,
				'pro' =>$provincial_councils
		]);
	}

	public function minSum(){
		ini_set("pcre.backtrack_limit","6000000");
		$addedBy = DB::selectone("SELECT name FROM employees WHERE id=".Auth::user()->id." AND deleted_at is null");
		$minData = DB::select('SELECT d.id,d.name,GROUP_CONCAT(DISTINCT(d1.name) ORDER BY d1.id) as sub,GROUP_CONCAT(DISTINCT(d1.id) ORDER BY d1.id) as subId
								FROM departments d
								LEFT JOIN departments d1 ON d1.parent=d.id
								WHERE d.id!=1 AND d.dept_type="Ministry" AND d.deleted_at is null 
								GROUP BY d.id,d.name');
		$pdfData     = array('rpt_data' => $minData,'prepared' => $addedBy,'type'=>'min');
		$pdf         = PDF::loadView('la.reports.pdf_rpt_min',compact('pdfData'),[],['title'=>'PUBAD-PSRIS']);
		return $pdf->download('summary_min.pdf');
	}

	public function statMinSum(){
		ini_set("pcre.backtrack_limit","6000000");
		$addedBy     = DB::selectone("SELECT name FROM employees WHERE id=".Auth::user()->id." AND deleted_at is null");
		$statMinData = DB::select('SELECT d.id,d.name,GROUP_CONCAT(DISTINCT(d1.name) ORDER BY d1.id) as sub,GROUP_CONCAT(DISTINCT(d1.id) ORDER BY d1.id) as subId
								FROM departments d
								LEFT JOIN departments d1 ON d1.parent=d.id
								WHERE d.id!=1 AND d.dept_type="State Ministry" AND d.deleted_at is null 
								GROUP BY d.id,d.name');
		$pdfData     = array('rpt_data' => $statMinData,'prepared' => $addedBy,'type'=>'stat_min');
		$pdf         = PDF::loadView('la.reports.pdf_rpt_min',compact('pdfData'),[],['title'=>'PUBAD-PSRIS']);
		return $pdf->download('summary_stat_min.pdf');
	}

	public function proviSum(){
		ini_set("pcre.backtrack_limit","6000000");
		$addedBy     = DB::selectone("SELECT name FROM employees WHERE id=".Auth::user()->id." AND deleted_at is null");
		$statMinData = DB::select('SELECT d.id,d.name,GROUP_CONCAT(DISTINCT(d1.name) ORDER BY d1.id) as sub,GROUP_CONCAT(DISTINCT(d1.id) ORDER BY d1.id) as subId
								FROM departments d
								LEFT JOIN departments d1 ON d1.parent=d.id
								WHERE d.id!=1 AND d.dept_type="Provincial Council" AND d.deleted_at is null 
								GROUP BY d.id,d.name');
		$pdfData     = array('rpt_data' => $statMinData,'prepared' => $addedBy,'type'=>'provincial');
		$pdf         = PDF::loadView('la.reports.pdf_rpt_min',compact('pdfData'),[],['title'=>'PUBAD-PSRIS']);
		return $pdf->download('summary_provincial.pdf');
	}

	public function getCount(){
		$addedBy         = DB::selectone("SELECT name FROM employees WHERE id=".Auth::user()->id." AND deleted_at is null");
		$allWithSubCount = DB::select('SELECT t2.name as lev2,GROUP_CONCAT(t3.name order by t3.id) as sub,t2.dept_type
											FROM departments AS t1
											LEFT JOIN departments AS t2 ON t2.parent = t1.id
											LEFT JOIN departments As t3 ON t3.parent = t2.id
											WHERE t1.id =1 AND t1.deleted_at is null AND t2.id!=1 AND t2.deleted_at is null AND t3.deleted_at is null
											GROUP BY t2.id 
											ORDER BY t2.dept_type ASC');
		$pdfData     = array('rpt_data' => $allWithSubCount,'prepared' => $addedBy);
		$pdf         = PDF::loadView('la.reports.pdf_count',compact('pdfData'),[],['title'=>'PUBAD-PSRIS']);
		return $pdf->download('count.pdf');
	}
}
