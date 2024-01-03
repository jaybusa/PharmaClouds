        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>spare-parts"><?php echo lang('spare_parts'); ?></a></li>
							<li><?php echo lang('new'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<a href="<?php echo base_url(); ?>spare-parts" class="btn btn-default btn-sm"><i class="fa fa-list"></i> 
							<?php echo lang('list_spare_parts'); ?>
						</a>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><small><?php echo lang('new'); ?></small> <?php echo lang('spare_part'); ?></h1>
                    </div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?php if(!empty($alertMsg)) { echo $alertMsg; } ?>
					</div>
				</div>
				<form action="#" method="post">
				<div class="row form vertical-form">
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="name"><?php echo lang('name'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="name" placeholder="<?php echo lang('name'); ?>" value="<?php echo set_value('name'); ?>" required />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="invoice_price"><?php echo lang('invoice_price')." <small>(".lang('sar_currency').")</small>"; ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="number" step="0.01" min="0" class="form-control" name="invoice_price" placeholder="<?php echo lang('invoice_price')." (".lang('sar_currency').")"; ?>" value="<?php echo set_value('invoice_price'); ?>" />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="hand_price"><?php echo lang('hand_price')." <small>(".lang('sar_currency').")</small>"; ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="number" step="0.01" min="0" class="form-control" name="hand_price" placeholder="<?php echo lang('hand_price')." (".lang('sar_currency').")"; ?>" value="<?php echo set_value('hand_price'); ?>" required />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="details"><?php echo lang('details'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<textarea class="form-control" name="details" placeholder="<?php echo lang('details'); ?>" required><?php echo set_value('details'); ?></textarea>
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
						<input type="submit" class="btn btn-primary" name="add_sparepart_btn" value="<?php echo lang('save'); ?>" />
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