
<?php $__env->startSection("contentheader_title", "Ministries/State Ministries/Provincial Councils"); ?>


<?php $__env->startSection('main-content'); ?>
<div id="page-content" class="profile2">
	<div class="bg-success clearfix">
		
		
	</div>

	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a href="<?php echo e(url(config('laraadmin.adminRoute') . '/')); ?>" data-toggle="tooltip" data-placement="right" title="Back to Dashboard"><i class="fa fa-chevron-left"></i></a></li>
		<li class="active"><a role="tab" data-toggle="tab" class="active min" href="#tab-info" data-target="#tab-info"><i class="fa fa-bars"></i> Ministries</a></li>
		<li class=""><a role="tab" data-toggle="tab" href="#tab-timeline" data-target="#tab-timeline" class="state_min"><i class="fa fa-bars"></i>State Ministries</a></li>
		<li class=""><a role="tab" data-toggle="tab" href="#tab-account-settings" data-target="#tab-account-settings" class="provincial"><i class="fa fa-bars"></i>Provincial Councils</a></li>
	</ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active fade in" id="tab-info">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-body">
						<?php if(isset($min)){
							for ($i=0; $i <sizeof($min) ; $i++) { 
								echo '<h3><b>'.$min[$i]->lev2.'</b></h3>';
								$sub  = explode(',',$min[$i]->sub);
								for ($a=0; $a <sizeof($sub) ; $a++) {
								    if($sub[$a]!=""){
										echo '<h4> --'.$sub[$a].'</h4>';								    	
								    } 
								}
							}
						}?>
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="tab-timeline">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-body">
						<?php if(isset($stat)){
							for ($i=0; $i <sizeof($stat) ; $i++) { 
								echo '<h3><b>'.$stat[$i]->lev2.'</b></h3>';
								$sub  = explode(',',$stat[$i]->sub);
								for ($a=0; $a <sizeof($sub) ; $a++) {
								    if($sub[$a]!=""){
										echo '<h4> --'.$sub[$a].'</h4>';								    	
								    } 
								}
							}
						}?>
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="tab-account-settings">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-body">
						<?php if(isset($pro)){
							for ($i=0; $i <sizeof($pro) ; $i++) { 
								echo '<h3><b>'.$pro[$i]->lev2.'</b></h3>';
								$sub  = explode(',',$pro[$i]->sub);
								for ($a=0; $a <sizeof($sub) ; $a++) {
								    if($sub[$a]!=""){
										echo '<h4> --'.$sub[$a].'</h4>';								    	
								    } 
								}
							}
						}?>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(function () {
	$('.min').click(function(){
		$('#tab-timeline').hide();
		$('#tab-account-settings').hide();	
		$('#tab-info').show();	
	})
	$('.state_min').click(function(){
		$('#tab-info').hide();
		$('#tab-account-settings').hide();	
		$('#tab-timeline').show();	
	})
	$('.provincial').click(function(){
		$('#tab-info').hide();
		$('#tab-timeline').hide();		
		$('#tab-account-settings').show();	
	})
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('la.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>