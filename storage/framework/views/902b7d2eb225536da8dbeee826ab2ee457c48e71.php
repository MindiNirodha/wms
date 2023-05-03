<?php $__env->startSection("contentheader_title", "All establishments"); ?>
<?php $__env->startSection("contentheader_description", "All establishments listing"); ?>
<?php $__env->startSection("section", "All establishments"); ?>
<?php $__env->startSection("sub_section", "Listing"); ?>
<?php $__env->startSection("htmlheader_title", "All establishments Listing"); ?>

<?php $__env->startSection("headerElems"); ?>
<?php if(LAFormMaker::la_access("All_establishments", "create")) { ?>
	<!-- <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add All establishment</button> -->
<?php } ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("main-content"); ?>

<?php if(count($errors) > 0): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach($errors->all() as $error): ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php //print_r($senior);?>
<div class="box box-success">
	<div class="modal-header"></div>		
	<div class="box-body">
		<!-- <?php echo Form::open(['action' => 'LA\All_establishmentsController@store', 'id' => 'all_establishment-add-form']); ?> -->
		<?php echo LAFormMaker::input($module, 'institute_name'); ?>
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
		<!-- <?php echo Form::submit( 'Submit', ['class'=>'btn btn-success']); ?> -->
	</div>
</div>

<?php if(LAFormMaker::la_access("All_establishments", "create")) { ?>
<!-- <div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add All establishment</h4>
			</div>
			<?php echo Form::open(['action' => 'LA\All_establishmentsController@store', 'id' => 'all_establishment-add-form']); ?>

			<div class="modal-body">
				<div class="box-body">
                    <?php echo LAFormMaker::form($module); ?>
					
					<?php /*
					<?php echo LAFormMaker::input($module, 'institute_name'); ?>
					<?php echo LAFormMaker::input($module, 'year'); ?>
					<?php echo LAFormMaker::input($module, 'senior'); ?>
					<?php echo LAFormMaker::input($module, 'secondary'); ?>
					<?php echo LAFormMaker::input($module, 'teritory'); ?>
					<?php echo LAFormMaker::input($module, 'primary'); ?>
					<?php echo LAFormMaker::input($module, 'total'); ?>
					*/ ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<?php echo Form::submit( 'Submit', ['class'=>'btn btn-success']); ?>

			</div>
			<?php echo Form::close(); ?>

		</div>
	</div>
</div> -->
<?php } ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('la-assets/plugins/datatables/datatables.min.css')); ?>"/>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('la-assets/plugins/datatables/datatables.min.js')); ?>"></script>
<script>
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "<?php echo e(url(config('laraadmin.adminRoute') . '/all_establishment_dt_ajax')); ?>",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		<?php if($show_actions): ?>
		columnDefs: [ { orderable: false, targets: [-1] }],
		<?php endif; ?>
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
				url: "<?php echo e(url(config('laraadmin.adminRoute').'/saveEst')); ?>",	
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make("la.layouts.app", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>