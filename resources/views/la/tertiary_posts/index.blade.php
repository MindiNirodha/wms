@extends("la.layouts.app")

@section("contentheader_title", "Tertiary Posts")
@section("contentheader_description", "Tertiary Posts listing")
@section("section", "Tertiary Posts")
@section("sub_section", "Listing")
@section("htmlheader_title", "Tertiary Posts Listing")

@section("headerElems")
@la_access("Tertiary_Posts", "create")
	<button class="btn btn-success btn-sm pull-right add-teritory" data-toggle="modal" data-target="#AddModal">Add Tertiary Post</button>
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
@if(Session::has('duplicate_message'))
	<div class="alert {{ Session::get('alert-class', 'alert-danger') }}" id="duplicateMessage">{{ Session::get('duplicate_message') }}</div>
@endif
<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="example1" class="table table-bordered">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
			@endforeach
			@if($show_actions)
			<th>Actions</th>
			@endif
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
	</div>
</div>

@la_access("Tertiary_Posts", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog tertiary-post-form" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Tertiary Post</h4>
			</div>
			{!! Form::open(['action' => 'LA\Tertiary_postsController@store', 'id' => 'tertiary_post-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
                    @la_form($module)
					
					{{--
					@la_input($module, 'institute_name')
					@la_input($module, 'designation')
					@la_input($module, '2005')
					@la_input($module, '2006')
					@la_input($module, '2007')
					@la_input($module, '2008')
					@la_input($module, '2009')
					@la_input($module, '2010')
					@la_input($module, '2011')
					@la_input($module, '2012')
					@la_input($module, '2013')
					@la_input($module, '2014')
					@la_input($module, '2015')
					@la_input($module, '2016')
					@la_input($module, '2017')
					@la_input($module, '2018')
					@la_input($module, '2019')
					@la_input($module, '2020')
					@la_input($module, '2021')
					@la_input($module, 'total')
					--}}
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="institute_name" value="<?php echo $dept_id;?>">
				<input type="hidden" name="url" value="<?php echo $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endla_access

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	var institute_name = $('input[name="institute_name"]').val();
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: {
            	"url": "{{ url(config('laraadmin.adminRoute').'/tertiary_post_dt_ajax')}}",	
            	"type": "GET",
            	"data": ({institute_name:institute_name})
        		},
        // ajax: "{{ url(config('laraadmin.adminRoute') . '/tertiary_post_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$('select[name="institute_name"]').val(null).trigger("change");
	@if(isset($dept_id))
	$('select[name="institute_name"]').val(<?php echo $dept_id;?>).change();	
	@endif	 	
	$('select[name="institute_name"]').attr("disabled", true);
	$(".tertiary-post-form .form-group input").addClass("numberbox-tertiary");
	$(".tertiary-post-form .form-group:nth-of-type(1) input").removeClass("numberbox-tertiary");
	$(".tertiary-post-form .form-group:nth-of-type(2) input").removeClass("numberbox-tertiary");
	$(".tertiary-post-form .form-group:nth-last-of-type(1) input").removeClass("numberbox-tertiary");
	$(".tertiary-post-form .form-group:nth-last-of-type(1) input").attr('id',"total_count");
	$('.form-group').on('input', function(){
	 	var totalSum = 0;
	 	$('.numberbox-tertiary').each(function(){
	 		var inputVal = $(this).val();
	 		
	 		if($.isNumeric(inputVal)){
	 			totalSum = totalSum + parseFloat(inputVal);
	 			console.log("totalsum",totalSum);
	 		}
	 	});
	 	$('#total_count').val(totalSum);
	 });
	 
	$("#tertiary_post-add-form").validate({
		
	});
	$('.numberbox-tertiary').keyup(function(){
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$('.add-teritory').click(function(){
		$('input[name="designation"]').val('');
		$('input[name="total"]').val('');
		$('.numberbox-tertiary').val('');
	})
	setTimeout(function() {$('#duplicateMessage').fadeOut('fast');}, 3000);
});
</script>
@endpush
<style type="text/css">
	.tertiary-post-form .box-body{
		display: inline-block;
	}
	.tertiary-post-form .form-group{
		display: inline-block;
		display: inline-block;
    	margin-right: 10px;
    	width: 58px;	
	}

	.tertiary-post-form .form-group label{
		font-size: 12px;
	}
	
	
	.tertiary-post-form .form-group:nth-of-type(1) {
		display: block;
		margin-right:0;
    	width:100%;
	}

	.tertiary-post-form .form-group:nth-of-type(2) {
		display: block;
		margin-right:0;
    	width:100%;
	}

	.tertiary-post-form .form-group:nth-last-of-type(1) {

		float: right;
		width: 100px;
	}
</style>