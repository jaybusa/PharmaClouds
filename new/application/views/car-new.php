		<!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>cars"><?php echo lang('cars'); ?></a></li>
							<li><?php echo lang('new'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<a href="<?php echo base_url(); ?>cars" class="btn btn-default btn-sm"><i class="fa fa-list"></i> 
							<?php echo lang('list_cars'); ?>
						</a>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><small><?php echo lang('new'); ?></small> <?php echo lang('car'); ?></h1>
                    </div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?php if(!empty($alertMsg)) { echo $alertMsg; } ?>
					</div>
				</div>
				<form action="#" method="post" id="newCarForm" enctype="multipart/form-data">
				<div class="row form vertical-form">
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="car_id"><?php echo lang('car_id'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="car_id" placeholder="<?php echo lang('car_id'); ?>" value="<?php echo set_value('car_id'); ?>" required />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="owner"><?php echo lang('owner'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<select class="form-control chosen <?php if($config['dir']=="rtl") echo "chosen-rtl"; ?>" name="owner" required="true">
								<option value=""><?php echo lang('select'); ?></option>
								<?php foreach($customers as $customer) { ?>
									<option value="<?php echo $customer->id; ?>" <?php echo set_select('owner',$customer->id); ?>><?php echo $customer->name; ?></option>
								<?php } ?>
							</select>
							<div class="input-error"></div>
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="model"><?php echo lang('model'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<select name="model" class="form-control chosen <?php if($config['dir']=="rtl") echo "chosen-rtl"; ?>"  required="true">
								<option value=""><?php echo lang('select'); ?></option>
								<?php for($i=2050;$i>=1950;$i--) { ?>
								<option value="<?php echo $i; ?>" <?php echo set_select('model',$i); ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
							<div class="input-error"></div>
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="type"><?php echo lang('type'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<select class="form-control chosen <?php if($config['dir']=="rtl") echo "chosen-rtl"; ?>" name="type"  required="true">
								<option value=""><?php echo lang('select'); ?></option>
								<?php foreach($car_types as $car_type) { ?>
									<option value="<?php echo $car_type->id; ?>" <?php echo set_select('type',$car_type->id); ?>><?php echo $car_type->name; ?></option>
								<?php } ?>
							</select>
							<div class="input-error"></div>
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="picture"><?php echo lang('picture'); ?></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="file" name="picture" />
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
					<div class="col-md-12 submit text-center">
						<input type="submit" class="btn btn-primary" name="add_car_btn" value="<?php echo lang('save'); ?>" />
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
	$(document).ready(function(){
		$("select[name=owner]").chosen();
		// validation of chosen on change
		if ($("select[name=owner]").length > 0) {
			$("select[name=owner]").each(function() {
				if ($(this).attr('required') !== undefined) {
					$(this).on("change", function() {
						$(this).valid();
					});
				}
			});
		}
		$("select[name=model]").chosen();
		// validation of chosen on change
		if ($("select[name=model]").length > 0) {
			$("select[name=model]").each(function() {
				if ($(this).attr('required') !== undefined) {
					$(this).on("change", function() {
						$(this).valid();
					});
				}
			});
		}
		$("select[name=type]").chosen();
		// validation of chosen on change
		if ($("select[name=type]").length > 0) {
			$("select[name=type]").each(function() {
				if ($(this).attr('required') !== undefined) {
					$(this).on("change", function() {
						$(this).valid();
					});
				}
			});
		}
	});
	</script>