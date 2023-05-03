<?php $__env->startSection("contentheader_title", "Reports"); ?>
<?php $__env->startSection("contentheader_description", "Reports"); ?>
<?php $__env->startSection("section", "Reports"); ?>
<?php $__env->startSection("sub_section", ""); ?>
<?php $__env->startSection("htmlheader_title", "Reports"); ?>

<?php $__env->startSection("headerElems"); ?>
<?php if(LAFormMaker::la_access("Reports", "create")) { ?>
	<!-- <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Report</button> -->
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

<?php if(LAFormMaker::la_access("Reports", "create")) { ?>
<!-- <div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel"> -->
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
				<h4 class="modal-title" id="myModalLabel">Create Report</h4>
			</div>
			<?php echo Form::open(['action' => 'LA\ReportsController@store', 'id' => 'report-add-form']); ?>

			<div class="modal-body">
				<div class="box-body">
                    <?php echo LAFormMaker::form($module); ?>
					
					<?php /*
					<?php echo LAFormMaker::input($module, 'rept_type'); ?>
					<?php echo LAFormMaker::input($module, 'rpt_min'); ?>
					<?php echo LAFormMaker::input($module, 'rpt_category'); ?>
					*/ ?>
				</div>
			</div>
			<div class="modal-footer">				
				<?php echo Form::submit( 'Generate Report', ['class'=>'btn btn-success submit_data']); ?>

				<button type="button" class="btn btn-reset">Cancel</button>
			</div>
			<div class="modal-footer">
				<?php if(Auth::user()->hasRole('SUPER_ADMIN')): ?>
				<?php echo Form::close(); ?>

				<?php echo Form::open(['action' => 'LA\ReportsController@minSum', 'id' => 'report-min']); ?>

				<?php echo Form::submit( 'Summary - Ministry', ['class'=>'btn btn-danger']); ?>

				<?php echo Form::close(); ?>

				<br>
				<?php echo Form::open(['action' => 'LA\ReportsController@statMinSum', 'id' => 'report-stat_min']); ?>

				<?php echo Form::submit( 'Summary - State Ministry', ['class'=>'btn btn-info']); ?>

				<?php echo Form::close(); ?>

				<br>
				<?php echo Form::open(['action' => 'LA\ReportsController@proviSum', 'id' => 'report-pro_coun']); ?>

				<?php echo Form::submit( 'Summary - Provincial Council', ['class'=>'btn btn-success']); ?>

				<?php echo Form::close(); ?>

				<br>
				<?php echo Form::open(['action' => 'LA\ReportsController@getCount', 'id' => 'report-count']); ?>

				<?php echo Form::submit( 'Sub Department Count', ['class'=>'btn btn-danger']); ?>

				<?php echo Form::close(); ?>

				<?php endif; ?>
			</div>
		</div>
	</div>
<!-- </div> -->
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
        ajax: "<?php echo e(url(config('laraadmin.adminRoute') . '/report_dt_ajax')); ?>",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		<?php if($show_actions): ?>
		columnDefs: [ { orderable: false, targets: [-1] }],
		<?php endif; ?>
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
		url: "<?php echo e(url(config('laraadmin.adminRoute') . '/get_ministries')); ?>",				  
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make("la.layouts.app", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>