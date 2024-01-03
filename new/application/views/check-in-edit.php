        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>check-ins"><?php echo lang('check_ins'); ?></a></li>
							<li><?php echo lang('edit'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<a href="<?php echo base_url(); ?>check-ins" class="btn btn-default btn-sm"><i class="fa fa-list"></i> 
							<?php echo lang('list_check_ins'); ?>
						</a>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><small><?php echo lang('edit'); ?></small> <?php echo lang('check_in'); ?></h1>
                    </div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?php if(!empty($alertMsg)) { echo $alertMsg; } ?>
					</div>
				</div>
				<form action="#" method="post">
				<div class="row form vertical-form">
					
					<div class="col-md-6">
						<div class="form-group">
							<label for="car_id"><?php echo lang('car_id'); ?> <span class="required">*</span></label>
							<?php echo $check_in_data->car_id; ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="stage"><?php echo lang('stage'); ?> <span class="required">*</span></label>
							<select class="form-control" name="stage" required>
								<option value="1" <?php if($check_in_data->stage==1) echo "selected"; ?>><?php echo lang('new'); ?></option>
								<?php if(isset($permission_data['new_progress'])) { ?>
								<option value="2" <?php if($check_in_data->stage==2) echo "selected"; ?>><?php echo lang('in_progress'); ?></option>
								<?php } ?>
								<?php if(isset($permission_data['progress_pending'])) { ?>
								<option value="3" <?php if($check_in_data->stage==3) echo "selected"; ?>><?php echo lang('pending'); ?></option>
								<?php } ?>
								<?php if(isset($permission_data['pending_completed'])) { ?>
								<option value="4" <?php if($check_in_data->stage==4) echo "selected"; ?>><?php echo lang('completed'); ?></option>
								<?php } ?>
								
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="check_in"><?php echo lang('check_in'); ?> <span class="required">*</span></label>
							<input type="text" class="form-control" name="check_in" id="check_in" placeholder="<?php echo lang('check_in'); ?>" value="<?php echo date('m/d/Y g:i A',strtotime($check_in_data->check_in)); ?>" required />
						</div>
						<script type="text/javascript">
							$(function () {
								$('#check_in').datetimepicker();
							});
						</script>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="check_out"><?php echo lang('check_out'); ?> <span class="required">*</span></label>
							<input type="text" class="form-control" name="check_out" id="check_out" placeholder="<?php echo lang('check_out'); ?>" value="<?php if($check_in_data->check_out!=NULL) echo date('m/d/Y g:i A',strtotime($check_in_data->check_out)); ?>" />
						</div>
						<script type="text/javascript">
							$(function () {
								$('#check_out').datetimepicker();
							});
						</script>
					</div>
					
					<div class="col-md-12 submit text-center">
						<input type="submit" class="btn btn-primary" name="edit_check_in_btn" value="<?php echo lang('save'); ?>" />
					</div>
                </div>
				</form>
				<hr />
				<div class="row instructions">
					<div class="col-sm-12">
						<ul>
							<li>* <?php echo lang('required_fields'); ?>
						</ul>
					</div>
				</div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->