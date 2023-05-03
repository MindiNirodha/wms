<?php $__env->startSection("contentheader_title"); ?>
	<a href="<?php echo e(url(config('laraadmin.adminRoute') . '/tertiary_posts/'.$tertiary_post->institute_name)); ?>">Tertiary Post</a> :
<?php $__env->stopSection(); ?>
<?php $__env->startSection("contentheader_description", $tertiary_post->$view_col); ?>
<?php $__env->startSection("section", "Tertiary Posts"); ?>
<?php $__env->startSection("section_url", url(config('laraadmin.adminRoute') . '/tertiary_posts/'.$tertiary_post->institute_name)); ?>
<?php $__env->startSection("sub_section", "Edit"); ?>

<?php $__env->startSection("htmlheader_title", "Tertiary Posts Edit : ".$tertiary_post->$view_col); ?>

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
<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 tertiary-post-form				">
				<?php echo Form::model($tertiary_post, ['route' => [config('laraadmin.adminRoute') . '.tertiary_posts.update', $tertiary_post->id ], 'method'=>'PUT', 'id' => 'tertiary_post-edit-form']); ?>

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
                    <br>
					<div class="form-group">
						<?php $id = Crypt::encrypt($tertiary_post->institute_name);?>
						<?php echo Form::submit( 'Update', ['class'=>'btn btn-success']); ?> <button class="btn btn-default pull-right"><a href="<?php echo e(url(config('laraadmin.adminRoute') . '/tertiary_posts/'.$id)); ?>">Cancel</a></button>
						<input type="hidden" name="url" value="<?php echo e(url(config('laraadmin.adminRoute') . '/tertiary_posts/'.$id)); ?>">
					</div>
				<?php echo Form::close(); ?>

			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make("la.layouts.app", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>