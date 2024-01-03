        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>check-ins"><?php echo lang('check_ins'); ?></a></li>
							<li><?php echo lang('new'); ?></li>
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
                        <h1><small><?php echo lang('new'); ?></small> <?php echo lang('check_in'); ?></h1>
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
							<select class="form-control chosen <?php if($config['dir']=="rtl") echo "chosen-rtl"; ?>" name="car_id" required="true">
								<option value=""><?php echo lang('select'); ?></option>
								<?php if(!empty($cars)) foreach($cars as $car) { ?>
								<option value="<?php echo $car->id; ?>" <?php echo set_select('car_id', $car->id, False); ?>><?php echo $car->car_id; ?></option>
								<?php } ?>
							</select>
							<div class="input-error"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="check_in"><?php echo lang('check_in'); ?> <span class="required">*</span></label>
							<input type="text" class="form-control" name="check_in" id="check_in" placeholder="<?php echo lang('check_in'); ?>" value="<?php echo set_value('check_in'); ?>" required="true" />
						</div>
						<script type="text/javascript">
							$(function () {
								$('#check_in').datetimepicker();
							});
						</script>
					</div>
					
					<div class="col-md-12 submit text-center">
						<input type="submit" class="btn btn-primary" name="add_check_in_btn" value="<?php echo lang('save'); ?>" />
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