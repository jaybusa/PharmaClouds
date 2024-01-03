        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>customers"><?php echo lang('customers'); ?></a></li>
							<li><?php echo lang('new'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<a href="<?php echo base_url(); ?>customers" class="btn btn-default btn-sm"><i class="fa fa-list"></i> 
							<?php echo lang('list_customers'); ?>
						</a>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><small><?php echo lang('new'); ?></small> <?php echo lang('customer'); ?></h1>
                    </div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?php if(!empty($alertMsg)) { echo $alertMsg; } ?>
					</div>
				</div>
				<form action="#" id="newCustomerForm" method="post" onsubmit="return validateCustomerForm();">
				<div class="row form vertical-form">
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="name"><?php echo lang('full_name'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="name" placeholder="<?php echo lang('full_name'); ?>" value="<?php echo set_value('name'); ?>" required="true" />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="email"><?php echo lang('email'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="email" class="form-control" name="email" placeholder="<?php echo lang('email_id'); ?>" value="<?php echo set_value('email'); ?>" required="true" />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="phone"><?php echo lang('phone'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="phone" placeholder="<?php echo lang('phone'); ?>" value="<?php echo set_value('phone'); ?>" required="true" onfocusout="return validateNewCustomerForm();" />
							<div class="field_error" id="phone_error"></div>
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="type"><?php echo lang('type'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<select name="type" class="form-control chosen <?php if($config['dir']=="rtl") echo "chosen-rtl"; ?>" required="true">
								<option value=""><?php echo lang('select'); ?></option>
								<?php if(!empty($customer_types)) foreach($customer_types as $customer_type) { ?>
								<option value="<?php echo $customer_type->id; ?>" <?php if($customer_type->id==$ctrl->input->post('type')) echo "selected"; ?>><?php echo $customer_type->name; ?></option>
								<?php } ?>
							</select>
							<div class="input-error"></div>
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
							  <input type="checkbox" name="status" value="1" <?php if(isset($_POST['status'])) echo "checked"; ?>>
							  <div class="slider round"></div>
							</label>
						</div>
					</div>
					<div class="col-md-12 submit text-center">
						<input type="submit" class="btn btn-primary" name="add_customer_btn" value="<?php echo lang('save'); ?>" />
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