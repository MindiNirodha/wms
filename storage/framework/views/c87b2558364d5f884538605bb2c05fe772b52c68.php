<?php $__env->startSection("contentheader_title", "Employees"); ?>
<?php $__env->startSection("contentheader_description", "employees listing"); ?>
<?php $__env->startSection("section", "Employees"); ?>
<?php $__env->startSection("sub_section", "Listing"); ?>
<?php $__env->startSection("htmlheader_title", "Employees Listing"); ?>

<?php $__env->startSection("headerElems"); ?>
<?php if(LAFormMaker::la_access("Employees", "create")) { ?>
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Employee</button>
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

<?php if(LAFormMaker::la_access("Employees", "create")) { ?>
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Employee</h4>
			</div>
			<?php echo Form::open(['action' => 'LA\EmployeesController@store', 'id' => 'employee-add-form']); ?>

			<div class="modal-body">
				<div class="box-body">
                    <?php echo LAFormMaker::form($module); ?>
					
					<?php /*
					<?php echo LAFormMaker::input($module, 'name'); ?>
					<?php echo LAFormMaker::input($module, 'designation'); ?>
					<?php echo LAFormMaker::input($module, 'gender'); ?>
					<?php echo LAFormMaker::input($module, 'mobile'); ?>
					<?php echo LAFormMaker::input($module, 'mobile2'); ?>
					<?php echo LAFormMaker::input($module, 'email'); ?>
					<?php echo LAFormMaker::input($module, 'dept'); ?>
					<?php echo LAFormMaker::input($module, 'city'); ?>
					<?php echo LAFormMaker::input($module, 'address'); ?>
					<?php echo LAFormMaker::input($module, 'about'); ?>
					<?php echo LAFormMaker::input($module, 'date_birth'); ?>
					<?php echo LAFormMaker::input($module, 'date_hire'); ?>
					<?php echo LAFormMaker::input($module, 'date_left'); ?>
					<?php echo LAFormMaker::input($module, 'salary_cur'); ?>
					*/ ?>
					<?php if(Auth::user()->hasRole('SUPER_ADMIN')): ?>
					<div class="form-group">
						<label for="role">Role* :</label>
						<select class="form-control" required="1" data-placeholder="Select Role" rel="select2" name="role">
							<?php $roles = App\Role::all(); ?>
							<?php foreach($roles as $role): ?>
								<?php if($role->id != 1): ?>
									<option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="modal-footer">
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
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "<?php echo e(url(config('laraadmin.adminRoute') . '/employee_dt_ajax')); ?>",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		<?php if($show_actions): ?>
		columnDefs: [ { orderable: false, targets: [-1] }],
		<?php endif; ?>
	});
	$("#employee-add-form").validate({
		
	});
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make("la.layouts.app", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>