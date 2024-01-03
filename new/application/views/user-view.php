        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>users"><?php echo lang('users'); ?></a></li>
							<li><?php echo $s_user_data->name; ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['user_new'])) { ?>
						<a href="<?php echo base_url(); ?>users/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('user'); ?></a>
						<?php } ?>
						<a href="<?php echo base_url(); ?>users" class="btn btn-default btn-sm"><i class="fa fa-list"></i> <?php echo lang('list_users'); ?></a>
					</div>
				</div>
				
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo $s_user_data->name; ?> <small><?php echo $s_user_data->role_name; ?></small></h1>
                    </div>
					<div class="row">
						<div class="col-sm-12">
							<?php if(!empty($alertMsg)) { echo $alertMsg; } ?>
						</div>
					</div>
					<div class="col-sm-4 col-md-3">
						<?php if(!empty($s_user_data->picture)) {
							$image=base_url()."uploads/users/".$s_user_data->picture;
						} else {
							$image=base_url()."uploads/default-user.png";
						} ?>
						<img src="<?php echo $image; ?>" class="img-responsive" />
					</div>
					<div class="col-sm-8 col-md-9 view-data">
						<table class="table table-responsive">
							<tr>
								<th class="text-right"><?php echo lang('username'); ?></th><td class="text-left"><?php echo $s_user_data->username; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('email'); ?></th><td class="text-left"><?php echo $s_user_data->email; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('phone'); ?></th><td class="text-left"><?php echo $s_user_data->phone; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('status'); ?></th><td class="<?php echo $s_user_data->state_class; ?> text-left"><i class="fa fa-circle"></i> <?php echo $s_user_data->state_name; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('registered_on'); ?></th><td class="text-left"><?php echo date('j M, o g:i A',strtotime($s_user_data->createdon)); ?></td>
							</tr>
						</table>
						<a href="#" class="btn btn-default" data-toggle="modal" data-target="#passChange"><i class="fa fa-key"></i> <?php echo lang('change_password'); ?></a>
						<a href="<?php echo base_url(); ?>sessions/view/<?php echo $s_user_data->username; ?>" class="btn btn-default"><i class="fa fa-globe"></i> <?php echo lang('view_sessions'); ?></a>
					</div>
				</div>
				
				<!-- Modal -->
				<div id="passChange" class="modal fade" role="dialog">
				  <div class="modal-dialog">
					<!-- Modal content-->
					<form action="#" method="post">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><?php echo lang('change_password'); ?></h4>
					  </div>
					  <div class="modal-body">
						<?php if($user_data->role!=3) { ?>
						<div class="form-group">
							<input type="password" name="curpass" class="form-control" placeholder="<?php echo lang('current_password'); ?>" required />
						</div>
						<?php } ?>
						<div class="form-group">
							<input type="password" name="pass" class="form-control" placeholder="<?php echo lang('new_password'); ?>" required />
						</div>
						<div class="form-group">
							<input type="password" name="conpass" class="form-control" placeholder="<?php echo lang('confirm_password'); ?>" required />
						</div>
					  </div>
					  
					  <div class="modal-footer">
						<input type="submit" class="btn btn-default" value="<?php echo lang('change'); ?>" name="change_pass_btn" />
					  </div>
					</div>
					</form>
				  </div>
				</div>

				<div class="row">
                    <div class="col-lg-12">
                        <h2><?php echo lang('activities'); ?></h2>
                    </div>
					<div class="col-sm-12 data">
						<table class="table table-responsive table-striped">
							<tr>
								<th class="timestamp text-center"><?php echo lang('time_gmt'); ?></th>
								<th class="text-center"><?php echo lang('user_ip'); ?></th>
								<th><?php echo lang('activity'); ?></th>
							</tr>
							<?php if(!empty($activities)) foreach($activities as $activity) { ?>
							<tr>
								<td><?php echo date('j M, o g:i A',strtotime($activity->createdon)); ?></td>
								<td><?php echo $activity->ip; ?></td>
								<td class="text-left"><?php echo $activity->msg; ?></td>
							</tr>
							<?php } else { ?>
								<tr><td colspan="6"><span class="not-available"><?php echo lang('not_available'); ?></span></td></tr>
							<?php } ?>
						</table>
					</div>
				</div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->