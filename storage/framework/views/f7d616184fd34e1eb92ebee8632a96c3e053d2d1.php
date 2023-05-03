<?php $__env->startSection("contentheader_title", "Tertiary Posts"); ?>
<?php $__env->startSection("contentheader_description", "Tertiary Posts listing"); ?>
<?php $__env->startSection("section", "Tertiary Posts"); ?>
<?php $__env->startSection("sub_section", "Listing"); ?>
<?php $__env->startSection("htmlheader_title", "Tertiary Posts Listing"); ?>

<?php $__env->startSection("headerElems"); ?>
<?php if(LAFormMaker::la_access("Tertiary_Posts", "create")) { ?>
	<button class="btn btn-success btn-sm pull-right add-teritory" data-toggle="modal" data-target="#AddModal">Add Tertiary Post</button>
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
<?php if(Session::has('duplicate_message')): ?>
	<div class="alert <?php echo e(Session::get('alert-class', 'alert-danger')); ?>" id="duplicateMessage"><?php echo e(Session::get('duplicate_message')); ?></div>
<?php endif; ?>
<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="example1" class="table table-bordered">
		<thead>
		<tr class="success">
			<?php foreach( $listing_cols as $col ): ?>
			<th><?php echo e(isset($module->fields[$col]['label']) ? $module->fields[$col]['label'] : ucfirst($col)); ?></th>
			<?php endforeach; ?>
			<?php if($show_actions): ?>
			<th>Actions</th>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
	</div>
</div>

<?php if(LAFormMaker::la_access("Tertiary_Posts", "create")) { ?>
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog tertiary-post-form" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Tertiary Post</h4>
			</div>
			<?php echo Form::open(['action' => 'LA\Tertiary_postsController@store', 'id' => 'tertiary_post-add-form']); ?>

			<div class="modal-body">
				<div class="box-body">
                    <?php echo LAFormMaker::form($module); ?>
					
					<?php /*
					<?php echo LAFormMaker::input($module, 'institute_name'); ?>
					<?php echo LAFormMaker::input($module, 'designation'); ?>
					<?php echo LAFormMaker::input($module, '2005'); ?>
					<?php echo LAFormMaker::input($module, '2006'); ?>
					<?php echo LAFormMaker::input($module, '2007'); ?>
					<?php echo LAFormMaker::input($module, '2008'); ?>
					<?php echo LAFormMaker::input($module, '2009'); ?>
					<?php echo LAFormMaker::input($module, '2010'); ?>
					<?php echo LAFormMaker::input($module, '2011'); ?>
					<?php echo LAFormMaker::input($module, '2012'); ?>
					<?php echo LAFormMaker::input($module, '2013'); ?>
					<?php echo LAFormMaker::input($module, '2014'); ?>
					<?php echo LAFormMaker::input($module, '2015'); ?>
					<?php echo LAFormMaker::input($module, '2016'); ?>
					<?php echo LAFormMaker::input($module, '2017'); ?>
					<?php echo LAFormMaker::input($module, '2018'); ?>
					<?php echo LAFormMaker::input($module, '2019'); ?>
					<?php echo LAFormMaker::input($module, '2020'); ?>
					<?php echo LAFormMaker::input($module, '2021'); ?>
					<?php echo LAFormMaker::input($module, 'total'); ?>
					*/ ?>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="institute_name" value="<?php echo $dept_id;?>">
				<input type="hidden" name="url" value="<?php echo $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<?php echo Form::submit( 'Submit', ['class'=>'btn btn-success']); ?>

			</div>
			<?php echo Form::close(); ?>

		</div>
	</div>
</div>
<?php } ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('la-assets/plugins/datatables/datatables.min.css')); ?>"/>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('la-assets/plugins/datatables/datatables.min.js')); ?>"></script>
<script>
$(function () {
	var institute_name = $('input[name="institute_name"]').val();
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: {
            	"url": "<?php echo e(url(config('laraadmin.adminRoute').'/tertiary_post_dt_ajax')); ?>",	
            	"type": "GET",
            	"data": ({institute_name:institute_name})
        		},
        // ajax: "<?php echo e(url(config('laraadmin.adminRoute') . '/tertiary_post_dt_ajax')); ?>",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		<?php if($show_actions): ?>
		columnDefs: [ { orderable: false, targets: [-1] }],
		<?php endif; ?>
	});
	$('select[name="institute_name"]').val(null).trigger("change");
	<?php if(isset($dept_id)): ?>
	$('select[name="institute_name"]').val(<?php echo $dept_id;?>).change();	
	<?php endif; ?>	 	
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
<?php $__env->stopPush(); ?>
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
<?php echo $__env->make("la.layouts.app", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>