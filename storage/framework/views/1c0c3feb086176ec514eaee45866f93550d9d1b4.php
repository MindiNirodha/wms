<?php $__env->startSection('htmlheader_title'); ?> Dashboard <?php $__env->stopSection(); ?>
<?php $__env->startSection('contentheader_title'); ?> Dashboard <?php $__env->stopSection(); ?>
<?php $__env->startSection('contentheader_description'); ?> Organisation Overview <?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<!-- Main content -->
        <?php if(Auth::user()->hasRole('SUPER_ADMIN')): ?>
        <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <?php $deptCount = DB::selectOne('SELECT COUNT(DISTINCT(id)) as tdept FROM departments WHERE deleted_at is null AND id!=1 AND parent=1');?>
                  <h3><?php echo e($deptCount->tdept); ?></h3>
                  <p>Ministries/State Ministries</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="<?php echo e(url("admin/all_depts")); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <?php $repCount = DB::select('SELECT d.id,d.status as st,
                        (SELECT COUNT(*) FROM  senior_posts s WHERE s.institute_name = d.id AND s.deleted_at is null) +
                        (SELECT COUNT(*) FROM secondary_posts se WHERE se.institute_name = d.id AND se.deleted_at is null)+
                        (SELECT COUNT(*) FROM tertiary_posts t WHERE t.institute_name = d.id AND t.deleted_at is null) +
                        (SELECT COUNT(*) FROM primary_posts p WHERE p.institute_name = d.id AND p.deleted_at is null)as tc
                    FROM  departments d WHERE d.deleted_at is null AND d.id!=1 AND d.parent=1');
                    foreach ($repCount as $key => $value) {
                      if($value->tc==0 && ($value->st=='No Reply' || $value->st=='Nomination Received')){
                        unset($repCount[$key]);
                      }
                    }                    
                  ?>
                  <h3><?php echo e(count($repCount)); ?></h3>
                  <p>Replied</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="<?php echo e(url("admin/get_rep/rep")); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <?php $repNotCount = DB::select('SELECT d.id,d.status as st,
                        (SELECT COUNT(*) FROM  senior_posts s WHERE s.institute_name = d.id AND s.deleted_at is null) +
                        (SELECT COUNT(*) FROM secondary_posts se WHERE se.institute_name = d.id AND se.deleted_at is null)+
                        (SELECT COUNT(*) FROM tertiary_posts t WHERE t.institute_name = d.id AND t.deleted_at is null) +
                        (SELECT COUNT(*) FROM primary_posts p WHERE p.institute_name = d.id AND p.deleted_at is null)as tc
                    FROM  departments d WHERE d.deleted_at is null AND d.id!=1 AND d.parent=1');
                    foreach ($repNotCount as $key => $value) {
                      if($value->st!='No Reply'){
                        unset($repNotCount[$key]);
                      }
                    }//print_r($repNotCount);
                      ?>
                  <h3><?php echo e(count($repNotCount)); ?></h3>
                  <p>Not Replied</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="<?php echo e(url("admin/get_rep/notrep")); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>1</h3>
                  <p>Report(s)</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="<?php echo e(url("admin/reports")); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
          </div><!-- /.row -->
          <br><br><br><br><br>
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <?php $seniorCount = DB::selectOne('SELECT COUNT(DISTINCT(id)) as all_senior FROM senior_posts WHERE deleted_at is null');?>
                  <h3><?php echo e($seniorCount->all_senior); ?></h3>
                  <p>Senior Posts</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="<?php echo e(url("admin/senior_posts")); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <?php $seconCount = DB::selectOne('SELECT COUNT(DISTINCT(id)) as all_secon FROM secondary_posts WHERE deleted_at is null');?>
                  <h3><?php echo e($seconCount->all_secon); ?></h3>
                  <p>Secondary Posts</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="<?php echo e(url("admin/secondary_posts")); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                  <?php $terCount = DB::selectOne('SELECT COUNT(DISTINCT(id)) as all_ter FROM tertiary_posts WHERE deleted_at is null');?>
                  <h3><?php echo e($terCount->all_ter); ?></h3>
                  <p>Teritory Posts</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="<?php echo e(url("admin/tertiary_posts")); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <?php $priCount = DB::selectOne('SELECT COUNT(DISTINCT(id)) as all_pri FROM primary_posts WHERE deleted_at is null');?>
                  <h3><?php echo e($priCount->all_pri); ?></h3>
                  <p>Primary Posts</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="<?php echo e(url("admin/primary_posts")); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
        <?php else: ?>
        <section class="content">
        <?php if(LAFormMaker::la_access("Departments", "create")) { ?>
          <a href="<?php echo e(url('/admin/departments')); ?>" class="btn btn-success">Add Department</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <a href="<?php echo e(url('/admin/reports')); ?>" class="btn btn-danger">Create Report</a></br></br>
          <!-- <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Department</button> -->
        <?php } ?>

          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <?php 
                  $instId      = DB::selectOne('select dept from  users u LEFT JOIN employees e ON e.id=u.context_id WHERE u.id=?',[Auth::user()->id]);
                  $insName     = DB::selectOne('select d.name from  users u LEFT JOIN employees e ON e.id=u.context_id LEFT JOIN departments d ON d.id=e.dept WHERE u.id=?',[Auth::user()->id]);
                  $parameter   = Crypt::encrypt($instId->dept);

                  ?>
                  <h5><b><?php echo $insName->name;?></b></h5>
                </div>
                <div class="small-box-footer">
                  <input type="hidden" id="did" value="<?php echo $instId->dept;?>">
                  <a href="<?php echo e(url("admin/senior_posts/".$parameter)); ?>" class="small-box-footer" style="color:black !important;"><u><b>Add Senior Posts</b></u>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="<?php echo e(url("admin/secondary_posts/".$parameter)); ?>" class="small-box-footer" style="color:black !important;"><u><b>Add Secondary Posts</b></u>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="<?php echo e(url("admin/tertiary_posts/".$parameter)); ?>" class="small-box-footer" style="color:black !important;"><u><b>Add Teritory Posts</b></u>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="<?php echo e(url("admin/primary_posts/".$parameter)); ?>" class="small-box-footer" style="color:black !important;"><u><b>Add Primary Posts</b></u>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="<?php echo e(url("admin/all_establishments/".$parameter)); ?>" class="small-box-footer loadform" style="color:black !important;"><u><b>All</b></u>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>                        
              </div>
            </div><!-- ./col -->
            <!-- get institutes-->
            <?php             
            //get sub depts
            $getSubDepts = DB::select('select id,name from departments where parent='.$instId->dept.' AND deleted_at is null');?>
            <?php for ($i=0; $i <sizeof($getSubDepts) ; $i++) { 
                  $para   = Crypt::encrypt($getSubDepts[$i]->id);
            ?>
                  <div class="col-lg-12 col-xs-12">
                  <!-- small box -->
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h5><b><?php echo $getSubDepts[$i]->name;?></b></h5>
                    </div>                   
                    <div class="small-box-footer">
                      <a href="<?php echo e(url("admin/senior_posts/".$para)); ?>" class="small-box-footer" style="color:black !important;"><u><b>Add Senior Posts</b></u>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="<?php echo e(url("admin/secondary_posts/".$para)); ?>" class="small-box-footer" style="color:black !important;"><u><b>Add Secondary Posts</b></u>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="<?php echo e(url("admin/tertiary_posts/".$para)); ?>" class="small-box-footer" style="color:black !important;"><u><b>Add Teritory Posts</b></u>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="<?php echo e(url("admin/primary_posts/".$para)); ?>" class="small-box-footer" style="color:black !important;"><u><b>Add Primary Posts</b></u>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <a href="<?php echo e(url("admin/all_establishments/".$para)); ?>" class="small-box-footer" style="color:black !important;"><u><b>All</b></u>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                  </div>
                </div><!-- ./col -->
            <?php }?>
            
           <!-- Main row -->  
        </section><!-- /.content -->
        <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<!-- Morris chart -->
<link rel="stylesheet" href="<?php echo e(asset('la-assets/plugins/morris/morris.css')); ?>">
<!-- jvectormap -->
<link rel="stylesheet" href="<?php echo e(asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css')); ?>">
<!-- Date Picker -->
<link rel="stylesheet" href="<?php echo e(asset('la-assets/plugins/datepicker/datepicker3.css')); ?>">
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo e(asset('la-assets/plugins/daterangepicker/daterangepicker-bs3.css')); ?>">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="<?php echo e(asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startPush('scripts'); ?>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?php echo e(asset('la-assets/plugins/morris/morris.min.js')); ?>"></script>
<!-- Sparkline -->
<script src="<?php echo e(asset('la-assets/plugins/sparkline/jquery.sparkline.min.js')); ?>"></script>
<!-- jvectormap -->
<script src="<?php echo e(asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')); ?>"></script>
<script src="<?php echo e(asset('la-assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')); ?>"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo e(asset('la-assets/plugins/knob/jquery.knob.js')); ?>"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo e(asset('la-assets/plugins/daterangepicker/daterangepicker.js')); ?>"></script>
<!-- datepicker -->
<script src="<?php echo e(asset('la-assets/plugins/datepicker/bootstrap-datepicker.js')); ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo e(asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')); ?>"></script>
<!-- FastClick -->
<script src="<?php echo e(asset('la-assets/plugins/fastclick/fastclick.js')); ?>"></script>
<!-- dashboard -->
<script src="<?php echo e(asset('la-assets/js/pages/dashboard.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function($) {
// 	$('body').pgNotification({
// 		style: 'circle',
// 		title: 'LaraAdmin',
// 		message: "Welcome to LaraAdmin...",
// 		position: "top-right",
// 		timeout: 0,
// 		type: "success",
// 		thumbnail: '<img width="40" height="40" style="display: inline-block;" src="<?php echo e(Gravatar::fallback(asset('la-assets/img/user2-160x160.jpg'))->get(Auth::user()->email, 'default')); ?>" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar2x.jpg" alt="">'
// 	}).show();
// })(window.jQuery);

$('.loadform').click(function(){
  var did  = $('#did').val();
  $.ajax({
        type: 'GET',
        url: "<?php echo e(url(config('laraadmin.adminRoute').'/all_establishments')); ?>", 
        data :({did:did}),
        dataType: 'json',
          success: function(data){
              if(data){  
                console.log(data)
              }
            }
        });
})
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('la.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>