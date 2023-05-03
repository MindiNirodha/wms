@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/senior_posts') }}">Senior post</a> :
@endsection
@section("contentheader_description", $senior_post->$view_col)
@section("section", "Senior posts")
@section("section_url", url(config('laraadmin.adminRoute') . '/senior_posts'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Senior posts Edit : ".$senior_post->$view_col)

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
				{!! Form::model($senior_post, ['route' => [config('laraadmin.adminRoute') . '.senior_posts.update', $senior_post->id ], 'method'=>'PUT', 'id' => 'senior_post-edit-form']) !!}
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
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/senior_posts') }}">Cancel</a></button>
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
	$("#senior_post-edit-form").validate({
		
	});
});
</script>
@endpush
