

<?php $__env->startSection("contentheader_title", "Ministries/State Ministries"); ?>
<?php $__env->startSection("contentheader_description",""); ?>
<?php $__env->startSection("section", "Reports"); ?>
<?php $__env->startSection("sub_section", ""); ?>
<?php $__env->startSection("htmlheader_title", "Reports"); ?>

<?php $__env->startSection("headerElems"); ?>
<?php if(LAFormMaker::la_access("Reports", "create")) { ?>
	<!-- <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Report</button> -->
<?php } ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("main-content"); ?>

<?php if(isset($param) && $param=='rep'){echo '<h3>Replied- Ministries/State Ministries</h3>';}else{echo '<h3>Not Replied - Ministries/State Ministries</h3>';}?>
<div class="box-body">
	<table id="example1" class="table table-bordered">
	<thead>
	<tr class="success">
		<th>Ministry/State Ministry</th>
		<th>Contact Person</th>
		<th>Designation</th>
		<th>Phone Number</th>
		<th>Status</th>
		<th>Count(Data)</th>
	</tr>
	</thead>
	<tbody>	
	<?php if(sizeof($data)>0){?>
	<?php foreach ($data as $key => $value) { ?>
	<tr>
		<td><?php echo e($value->dept); ?></td>
		<td><?php echo e($value->name); ?></td>
		<td><?php echo e($value->designation); ?></td>
		<td><?php echo e($value->mobile); ?></td>
		<td><?php echo e($value->st); ?></td>
		<td><?php echo e($value->tc); ?></td>
	</tr>
	<?php }}else{?>
	<tr><td colspan="4" align="center">No Data to Display</td></tr>	
	<?php } ?>	
	</tbody>
	</table>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('la-assets/plugins/datatables/datatables.min.css')); ?>"/>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('la-assets/plugins/datatables/datatables.min.js')); ?>"></script>
<script>
$(function () {
	var table = $("#example1").DataTable({"pageLength": 100});
    table.order( [ 4, 'desc' ] ).draw();
})
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("la.layouts.app", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>