<?php $__env->startSection("contentheader_title"); ?>
	<a href="<?php echo e(url(config('laraadmin.adminRoute') . '/employees')); ?>">Employees</a> :
<?php $__env->stopSection(); ?>
<?php $__env->startSection("contentheader_description", $employee->$view_col); ?>
<?php $__env->startSection("section", "Employees"); ?>
<?php $__env->startSection("section_url", url(config('laraadmin.adminRoute') . '/employees')); ?>
<?php $__env->startSection("sub_section", "Edit"); ?>

<?php $__env->startSection("htmlheader_title", "Employee Edit : ".$employee->$view_col); ?>

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

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<?php echo Form::model($employee, ['route' => [config('laraadmin.adminRoute') . '.employees.update', $employee->id ], 'method'=>'PUT', 'id' => 'employee-edit-form']); ?>

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
								<?php if($role->id != 1 || Entrust::hasRole("SUPER_ADMIN")): ?>
									<?php if($user->hasRole($role->name)): ?>
										<option value="<?php echo e($role->id); ?>" selected><?php echo e($role->name); ?></option>
									<?php else: ?>
										<option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>
					<?php endif; ?>
					<br>
					<div class="form-group">
						<?php echo Form::submit( 'Update', ['class'=>'btn btn-success']); ?> <button class="btn btn-default pull-right"><a href="<?php echo e(url(config('laraadmin.adminRoute') . '/employees')); ?>">Cancel</a></button>
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
	$("#employee-edit-form").validate({
		
	});
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("la.layouts.app", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>