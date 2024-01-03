<div id="sendEmail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content form">
			<form action="#" method="post">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?php echo lang('send_email'); ?></h4>
				</div>
				<div class="modal-body row">
					<div class="col-sm-12 form-group">
						<input type="email" class="form-control" placeholder="<?php echo lang('to'); ?>" />
					</div>
					<div class="col-sm-12 form-group">
						<input type="text" class="form-control" placeholder="<?php echo lang('subject'); ?>" />
					</div>
					<div class="col-sm-12 form-group">
						<textarea class="form-control" placeholder="<?php echo lang('message_body'); ?>" style="height:200px;"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-default" value="Send" />
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

		<!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><?php echo lang('settings'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php /*
						<a href="" class="btn btn-default btn-sm" data-toggle="modal" data-target="#sendEmail"><i class="fa fa-list"></i> 
							<?php echo lang('send_email'); ?>
						</a>
						*/ ?>
					</div>
				</div>
				<div class="errors">
					<?php if(validation_errors()) { ?><div class="alert alert-danger"><?php echo validation_errors(); ?></div><?php } ?>
					<?php if(isset($alertMsg)) { 
						if(is_array($alertMsg)) {
							foreach($alertMsg as $msg) echo $msg; 
						} else {
							echo $alertMsg;
						}
					} ?>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo lang('settings'); ?></h1>
                    </div>
				</div>
				<div class="row form vertical-form">
					<form action="<?php echo base_url(); ?>settings" method="post" enctype="multipart/form-data" name="settings-form">
						<div class="col-sm-4 col-md-3 text-right">
							<div class="form-group">
								<label for="picture"><?php echo lang('change_profile_picture'); ?></label>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="form-group">
								<?php if(!empty($user_data->picture)) {
									$image=base_url()."uploads/users/".$user_data->picture;
								} else {
									$image=base_url()."uploads/default-user.png";
								} ?>
								<img src="<?php echo $image; ?>" class="img-thumbnail" style="max-height:100px;margin-bottom:10px;" />
								<input type="file" name="picture" />
							</div>
						</div>
						<div class="col-sm-4 col-md-3 text-right">
							<div class="form-group">
								<label for="curr_pass"><?php echo lang('current_password'); ?></label>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="form-group">
								<input type="password" name="curr_pass" value="<?php echo set_value('curr_pass'); ?>" class="form-control" placeholder="<?php echo lang('current_password'); ?>" />
							</div>
						</div>
						<div class="col-sm-4 col-md-3 text-right">
							<div class="form-group">
								<label for="new_pass"><?php echo lang('new_password'); ?></label>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="form-group">
								<input type="password" name="new_pass" value="<?php echo set_value('new_pass'); ?>" pattern=".{8,}" title="8 characters minimum" class="form-control" placeholder="<?php echo lang('new_password'); ?>" />
							</div>
						</div>
						<div class="col-sm-4 col-md-3 text-right">
							<div class="form-group">
								<label for="con_pass"><?php echo lang('confirm_password'); ?></label>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="form-group">
								<input type="password" name="con_pass" value="<?php echo set_value('con_pass'); ?>" pattern=".{8,}" class="form-control" placeholder="<?php echo lang('confirm_password'); ?>" />
							</div>
						</div>
						<?php if(isset($permission_data['otp'])) { ?>
						<div class="col-sm-4 col-md-3 text-right">
							<div class="form-group">
								<label for="otp_status"><?php echo lang('enable_otp'); ?></label>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="form-group">
								<input type="checkbox" name="otp_status" value="1" <?php if($otp_status==1) echo "checked='true'"; ?> />
							</div>
						</div>
						<?php } ?>
						<div class="col-md-12 submit text-center">
							<input type="submit" name="settings-btn" class="btn btn-primary" value="<?php echo lang('save'); ?>" />
						</div>
					</form>
				</div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->