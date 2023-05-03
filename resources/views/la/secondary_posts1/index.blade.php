@extends("la.layouts.app")

@section("contentheader_title", "Secondary posts")
@section("contentheader_description", "Secondary posts listing")
@section("section", "Secondary posts")
@section("sub_section", "Listing")
@section("htmlheader_title", "Secondary posts Listing")

@section("headerElems")
@la_access("Secondary_posts", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Secondary post</button>
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

@la_access("Secondary_posts", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Secondary post</h4>
			</div>
			{!! Form::open(['action' => 'LA\Secondary_postsController@store', 'id' => 'secondary_post-add-form']) !!}
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
					--}}
				</div>
			</div>
			<div class="modal-footer">
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
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/secondary_post_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$("#secondary_post-add-form").validate({
		
	});
});
</script>
@endpush
