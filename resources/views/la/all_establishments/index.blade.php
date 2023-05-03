@extends("la.layouts.app")

@section("contentheader_title", "All establishments")
@section("contentheader_description", "All establishments listing")
@section("section", "All establishments")
@section("sub_section", "Listing")
@section("htmlheader_title", "All establishments Listing")

@section("headerElems")
@la_access("All_establishments", "create")
	<!-- <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add All establishment</button> -->
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
<?php //print_r($senior);?>
<div class="box box-success">
	<div class="modal-header"></div>		
	<div class="box-body">
		<!-- {!! Form::open(['action' => 'LA\All_establishmentsController@store', 'id' => 'all_establishment-add-form']) !!} -->
		@la_input($module, 'institute_name')
		<input type="hidden" name="institute_name" value="<?php echo $deptdata->id;?>">
		<table id="example12" class="table table-bordered">
		<thead>
		<tr class="success">
			<th>Year</th>
			<th style="text-align:right !important;">Senior</th>
			<th style="text-align:right !important;">Secondary</th>
			<th style="text-align:right !important;">Teritory</th>
			<th style="text-align:right !important;">Primary</th>
			<th style="text-align:right !important;">Total</th>
		</tr>
		</thead>
		<tbody>
			<?php for ($i=0; $i <17 ; $i++) {?>
				<tr><td><b><?php echo(2005+$i);?></b></td>
					<td align="right"><?php if(isset($senior)){echo $senior[$i];}?></td>
					<td align="right"><?php if(isset($secondary)){echo $secondary[$i];}?></td>
					<td align="right"><?php if(isset($teritory)){echo $teritory[$i];}?></td>
					<td align="right"><?php if(isset($primary)){echo $primary[$i];}?></td>
					<td align="right"><b><?php if(isset($senior)|| isset($secondary)||isset($teritory)||isset($primary)){$total=($senior[$i]+$secondary[$i]+$teritory[$i]+$primary[$i]); echo $total;}?></b></td>
					<!-- <td><input type="button" class="btn btn-warning edit" value="Edit" <?php //if(isset($saved)&& $saved[$i]->total==""){echo 'disabled';}?>>&nbsp; -->
						<!-- <input type="button" class="btn btn-success save" value="Save" <?php //if(isset($saved)&& $saved[$i]->total!=""){//echo 'disabled';}?>> -->
					</td>
				</tr>
			<?php }?>
		</tbody>
		</table>
		<!-- {!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} -->
	</div>
</div>

@la_access("All_establishments", "create")
<!-- <div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add All establishment</h4>
			</div>
			{!! Form::open(['action' => 'LA\All_establishmentsController@store', 'id' => 'all_establishment-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
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
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div> -->
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
        ajax: "{{ url(config('laraadmin.adminRoute') . '/all_establishment_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$("#all_establishment-add-form").validate({
		
	});
});
</script>
<script>
$(document).ready(function(){
	$('select[name="institute_name"]').val(null).trigger("change");
	$('select[name="institute_name"]').val(<?php if(isset($deptdata)){echo $deptdata->id;}?>).change();	 	
	$('select[name="institute_name"]').attr("disabled", true); 		
	$('#example12 tr td input').change(function(){
		var senior    =$(this).closest('tr').find('input[name="senior[]"]').val();
		if(senior==''){
			senior =0;
		}
		var secondary =$(this).closest('tr').find('input[name="secondary[]"]').val();
		if(secondary==''){
			secondary =0;
		}
		var teritory  =$(this).closest('tr').find('input[name="teritory[]"]').val();
		if(teritory==''){
			teritory =0;
		}
		var primary   =$(this).closest('tr').find('input[name="primary[]"]').val();
		if(primary==''){
			primary =0;
		}
		var total     = parseInt(senior)+parseInt(secondary)+parseInt(teritory)+parseInt(primary);
		$(this).closest('tr').find('input[name="total[]"]').val(total);
	})
	$('.save').click(function(){
			var senior    =$(this).closest('tr').find('input[name="senior[]"]').val();
			var secondary =$(this).closest('tr').find('input[name="secondary[]"]').val();
			var teritory  =$(this).closest('tr').find('input[name="teritory[]"]').val();
			var primary   =$(this).closest('tr').find('input[name="primary[]"]').val();
			var year      =$(this).closest('tr').find('input[name="year[]"]').val();
			var total     =$(this).closest('tr').find('input[name="total[]"]').val();
			if(total!=""){
				$.ajax({
				type: 'GET',
				url: "{{ url(config('laraadmin.adminRoute').'/saveEst')}}",	
				data :({senior:senior,secondary:secondary,teritory:teritory,primary:primary,year:year,total:total}),
				dataType: 'json',
			    success: function(data){
				    	if(data){  
				    		console.log(data)
				    		if(data.success==true){
				    			$( ".modal-header" ).append('<div class="alert alert-success"><strong>Success!</strong>Successfully Inserted.</div>');
				    			setTimeout(function(){$(".alert-success").fadeOut(1500);}, 2000);
				    		}else{
				    			$( ".modal-header" ).append('<div class="alert alert-danger"><strong>Failed!</strong>Inserting Failed.</div>');
				    			setTimeout(function(){$(".alert-danger").fadeOut(1500);}, 2000);
				    		}	
				    	}
			    	}
				});
			}else{
				alert('Please Enter Amount(s) to Senior/Secondary/Teritory/Primary cage(s)');
			}
			
	})
})
</script>
@endpush
