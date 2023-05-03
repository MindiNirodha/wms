@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/all_establishments') }}">All establishment</a> :
@endsection
@section("contentheader_description", $all_establishment->$view_col)
@section("section", "All establishments")
@section("section_url", url(config('laraadmin.adminRoute') . '/all_establishments'))
@section("sub_section", "Edit")

@section("htmlheader_title", "All establishments Edit : ".$all_establishment->$view_col)

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

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::model($all_establishment, ['route' => [config('laraadmin.adminRoute') . '.all_establishments.update', $all_establishment->id ], 'method'=>'PUT', 'id' => 'all_establishment-edit-form']) !!}
					@la_form($module)
					
					{{--
					@la_input($module, 'institute_name')
					@la_input($module, 'year')
					@la_input($module, 'senior')
					@la_input($module, 'secondary')
					@la_input($module, 'teritory')
					@la_input($module, 'primary')
					@la_input($module, 'total')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/all_establishments') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
	$("#all_establishment-edit-form").validate({
		
	});
});
</script>
@endpush
