@extends("la.layouts.app")

@section("contentheader_title", "Reports")
@section("contentheader_description", "Reports")
@section("section", "Reports")
@section("sub_section", "")
@section("htmlheader_title", "Reports")

@section("headerElems")
@la_access("Reports", "create")
	<!-- <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Report</button> -->
@endla_access
@endsection

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@la_access("Reports", "create")
<!-- <div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel"> -->
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
				<h4 class="modal-title" id="myModalLabel">Create Report</h4>
			</div>
			{!! Form::open(['action' => 'LA\ReportsController@store', 'id' => 'report-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
                    @la_form($module)
					
					{{--
					@la_input($module, 'rept_type')
					@la_input($module, 'rpt_min')
					@la_input($module, 'rpt_category')
					--}}
				</div>
			</div>
			<div class="modal-footer">				
				{!! Form::submit( 'Generate Report', ['class'=>'btn btn-success submit_data']) !!}
				<button type="button" class="btn btn-reset">Cancel</button>
			</div>
			<div class="modal-footer">
				@if(Auth::user()->hasRole('SUPER_ADMIN'))
				{!! Form::close() !!}
				{!! Form::open(['action' => 'LA\ReportsController@minSum', 'id' => 'report-min']) !!}
				{!! Form::submit( 'Summary - Ministry', ['class'=>'btn btn-danger']) !!}
				{!! Form::close() !!}
				<br>
				{!! Form::open(['action' => 'LA\ReportsController@statMinSum', 'id' => 'report-stat_min']) !!}
				{!! Form::submit( 'Summary - State Ministry', ['class'=>'btn btn-info']) !!}
				{!! Form::close() !!}
				<br>
				{!! Form::open(['action' => 'LA\ReportsController@proviSum', 'id' => 'report-pro_coun']) !!}
				{!! Form::submit( 'Summary - Provincial Council', ['class'=>'btn btn-success']) !!}
				{!! Form::close() !!}
				<br>
				{!! Form::open(['action' => 'LA\ReportsController@getCount', 'id' => 'report-count']) !!}
				{!! Form::submit( 'Sub Department Count', ['class'=>'btn btn-danger']) !!}
				{!! Form::close() !!}
				@endif
			</div>
		</div>
	</div>
<!-- </div> -->
@endla_access

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/report_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$('select[name="rpt_min"]').closest('div.form-group').hide();
	$("input[name=rept_type][value='All']").prop("checked",true);
	$("input[name=rpt_category][value='All']").prop("checked",true);
	$('input:radio[name="rept_type"]').change(function(){
		getMinistries();
		$('select[name="rpt_min"]').val('Select a Ministry').trigger('change');
		if($(this).val()=='Ministry'){			
			$('select[name="rpt_min"]').closest('div.form-group').show();
		}else{			
			$('select[name="rpt_min"]').closest('div.form-group').hide();
		}
	})
	
	$('.submit_data').click(function(e){
		var rpt_type = $('input:radio[name="rept_type"]:checked').val();
		if(rpt_type=="Ministry" && $('select[name="rpt_min"]').val()==""){
			e.preventDefault();
			alert("Please select a Ministry");
		}
		if(rept_type=='All'){
			$('select[name="rpt_min"]').val('Select a Ministry').trigger('change');
		}
	})

	$('.btn-reset').click(function(){
		$("input[name=rept_type][value='All']").prop("checked",true);
		$("input[name=rpt_category][value='All']").prop("checked",true);
		$('select[name="rpt_min"]').closest('div.form-group').hide();
		$('select[name="rpt_min"]').val('Select a Ministry').trigger('change');
	})
	$("#report-add-form").validate({
		
	});
});
function getMinistries(){
	$.ajax({
		type: 'GET',
		url: "{{ url(config('laraadmin.adminRoute') . '/get_ministries')}}",				  
		dataType: 'json',
	    success: function (data) {
	    	if(data){
	    		// console.log(data)
	    		$('select[name="rpt_min"] option[value]').remove();
	    		$('select[name="rpt_min"]').append("<option value=''>Please Select a Ministry</option>");
	    		for(var k in data){
			        var data1 = data[k];
			        $('select[name="rpt_min"]').append("<option value='"+ data1.id +"'>"+data1.text+"</option>");
		        }		    		
	    	}
	    }
	});
}
</script>
@endpush
