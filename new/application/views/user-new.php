        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>users"><?php echo lang('users'); ?></a></li>
							<li><?php echo lang('new'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<a href="<?php echo base_url(); ?>users" class="btn btn-default btn-sm"><i class="fa fa-list"></i> 
							<?php echo lang('list_users'); ?>
						</a>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><small><?php echo lang('new'); ?></small> <?php echo lang('user'); ?></h1>
                    </div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?php if(!empty($alertMsg)) { echo $alertMsg; } ?>
					</div>
				</div>
				<form action="#" method="post" enctype="multipart/form-data" onsubmit="return validateNewUserForm();">
				<div class="row form vertical-form">
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="picture"><?php echo lang('profile_picture'); ?></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="file" name="picture" />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="name"><?php echo lang('full_name'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="name" placeholder="<?php echo lang('full_name'); ?>" value="<?php echo set_value('name'); ?>" required />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="username"><?php echo lang('username'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="username" placeholder="<?php echo lang('username'); ?>" value="<?php echo set_value('username'); ?>" required />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="pass"><?php echo lang('password'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="password" class="form-control" name="pass" placeholder="<?php echo lang('password'); ?>" value="<?php echo set_value('pass'); ?>" required />
							<div class="field_error" id="pass_error"></div>
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="conpass"><?php echo lang('confirm_password'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="password" class="form-control" name="conpass" placeholder="<?php echo lang('confirm_password'); ?>" value="<?php echo set_value('conpass'); ?>" required />
							<div class="field_error" id="conpass_error"></div>
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="email"><?php echo lang('email'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="email" class="form-control" name="email" placeholder="<?php echo lang('email_id'); ?>" value="<?php echo set_value('email'); ?>" required />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="phone"><?php echo lang('phone'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="phone" placeholder="<?php echo lang('phone'); ?>" value="<?php echo set_value('phone'); ?>" required="true" />
							<div class="field_error" id="phone_error"></div>
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="role"><?php echo lang('role'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<select name="role" class="form-control" required>
								<option value=""><?php echo lang('select'); ?></option>
								<option value="1" <?php echo set_select('role', '1'); ?>><?php echo lang('spare_part_entry'); ?></option>
								<option value="2" <?php echo set_select('role', '2'); ?>><?php echo lang('data_entry'); ?></option>
								<option value="3" <?php echo set_select('role', '3'); ?>><?php echo lang('admin'); ?></option>
							</select>
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="status"><?php echo lang('status'); ?></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
					
							<label class="switch">
							  <input type="checkbox" name="status" <?php if(isset($_POST['status'])) echo "checked"; ?>>
							  <div class="slider round"></div>
							</label>
						</div>
					</div>
					<div class="col-md-12 submit text-center">
						<input type="submit" class="btn btn-primary" name="add_user_btn" value="<?php echo lang('save'); ?>" />
					</div>
                </div>
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