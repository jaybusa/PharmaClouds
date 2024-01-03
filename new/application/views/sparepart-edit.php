        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>check-ins/view/<?php echo url_id_encode($checkin_data->id);?>"><?php echo $checkin_data->car_id." ".lang('spare_parts'); ?></a></li>
							<li><?php echo lang('edit'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><small><?php echo lang('edit'); ?></small> <?php echo lang('spare_part'); ?></h1>
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
							<input type="text" class="form-control" name="name" placeholder="<?php echo lang('name'); ?>" value="<?php echo $sparepart_data->name; ?>" required />
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="invoice_id"><?php echo lang('invoice_id'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="number" step="1" min="0" class="form-control" name="invoice_id" placeholder="<?php echo lang('invoice_id'); ?>" value="<?php echo $sparepart_data->invoice_id; ?>" onkeyup="return validateInvoiceId();" required />
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="invoice_price"><?php echo lang('invoice_price')." <small>(".lang('sar_currency').")</small>"; ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="number" step="0.01" min="0" class="form-control" name="invoice_price" placeholder="<?php echo lang('invoice_price'); ?>" value="<?php echo $sparepart_data->invoice_price; ?>" onkeyup="return validateInvoicePrice();" required />
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="invoice_file"><?php echo lang('invoice_file')." <small>(".lang('sar_currency').")</small>"; ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<?php if(!$sparepart_data->invoice_file) echo "N/A"; else echo $sparepart_data->invoice_file; ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="hand_price"><?php echo lang('hand_price')." <small>(".lang('sar_currency').")</small>"; ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="number" step="0.01" min="0" class="form-control" name="hand_price" placeholder="<?php echo lang('hand_price'); ?>" value="<?php echo $sparepart_data->hand_price; ?>" onkeyup="return validateHandPrice();" required />
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="details"><?php echo lang('details'); ?></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<textarea name="details" class="form-control" placeholder="<?php echo lang('details'); ?>" required><?php echo $sparepart_data->details; ?></textarea>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-12 submit text-center">
						<input type="submit" class="btn btn-primary" name="edit_sparepart_btn" value="<?php echo lang('save'); ?>" />
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
	<script>
		function validateHandPrice() {
			var ele=$('input[name=hand_price]');
			if(!($.isNumeric(ele.val()))) {
				ele.val('');
			} else {
				if(ele.val()<0) {
					ele.val('');
				}
			}
		}
		function validateInvoicePrice() {
			var ele=$('input[name=invoice_price]');
			if(!($.isNumeric(ele.val()))) {
				ele.val('');
			} else {
				if(ele.val()<0) {
					ele.val('');
				}
			}
		}
		function validateInvoiceId() {
			var ele=$('input[name=invoice_id]');
			if(!($.isNumeric(ele.val()))) {
				ele.val('');
			} else {
				if(ele.val()<0) {
					ele.val('');
				}
			}
		}
	</script>