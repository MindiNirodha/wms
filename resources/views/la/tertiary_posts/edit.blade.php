@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/tertiary_posts/'.$tertiary_post->institute_name) }}">Tertiary Post</a> :
@endsection
@section("contentheader_description", $tertiary_post->$view_col)
@section("section", "Tertiary Posts")
@section("section_url", url(config('laraadmin.adminRoute') . '/tertiary_posts/'.$tertiary_post->institute_name))
@section("sub_section", "Edit")

@section("htmlheader_title", "Tertiary Posts Edit : ".$tertiary_post->$view_col)

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
<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 tertiary-post-form				">
				{!! Form::model($tertiary_post, ['route' => [config('laraadmin.adminRoute') . '.tertiary_posts.update', $tertiary_post->id ], 'method'=>'PUT', 'id' => 'tertiary_post-edit-form']) !!}
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
						<?php $id = Crypt::encrypt($tertiary_post->institute_name);?>
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/tertiary_posts/'.$id) }}">Cancel</a></button>
						<input type="hidden" name="url" value="{{url(config('laraadmin.adminRoute') . '/tertiary_posts/'.$id)}}">
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
	$('select[name="institute_name"]').closest('div.form-group').hide();
	$(".tertiary-post-form .form-group input").addClass("numberbox-tertiary");
	$(".tertiary-post-form .form-group:nth-of-type(1) input").removeClass("numberbox-tertiary");
	$(".tertiary-post-form .form-group:nth-of-type(2) input").removeClass("numberbox-tertiary");
	$(".tertiary-post-form").find('input[name="total"]').removeClass("numberbox-tertiary");
	// $(".tertiary-post-form .form-group:nth-last-of-type(1) input").attr('id',"total_count");
	 $('.form-group').on('input', function(){
	 	var totalSum = 0;
	 	$('.numberbox-tertiary').each(function(){
	 		var inputVal = $(this).val();
	 		
	 		if($.isNumeric(inputVal)){
	 			totalSum = totalSum + parseFloat(inputVal);
	 			console.log("totalsum",totalSum);
	 		}
	 	});
	 	$(".tertiary-post-form").find('input[name="total"]').val(totalSum);
	 	// $('#total_count').val(totalSum);
	 });
	$('.numberbox-tertiary').keyup(function(){
		this.value = this.value.replace(/[^0-9\.]/g,'');
	}) 
	setTimeout(function() {$('#duplicateMessage').fadeOut('fast');}, 3000);
	$("#tertiary_post-edit-form").validate({
		
	});
});
</script>
@endpush
