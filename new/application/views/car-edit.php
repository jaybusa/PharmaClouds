        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>cars"><?php echo lang('cars'); ?></a></li>
							<li><?php echo lang('edit'); ?></li>
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
                        <h1><small><?php echo lang('edit'); ?></small> <?php echo lang('car'); ?></h1>
                    </div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?php if(!empty($alertMsg)) { echo $alertMsg; } ?>
					</div>
				</div>
				<form action="#" method="post" id="editCarForm" enctype="multipart/form-data">
				<div class="row form vertical-form">
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="car_id"><?php echo lang('car_id'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<?php echo $car_data->car_id; ?>
							<input type="hidden" name="car_id" value="<?php echo $car_data->car_id; ?>" />
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="owner"><?php echo lang('owner'); ?> <span class="required">*</span></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<select class="form-control chosen <?php if($config['dir']=="rtl") echo "chosen-rtl"; ?>" name="owner"  required="true">
								<option value=""><?php echo lang('select'); ?></option>
								<?php foreach($customers as $customer) { ?>
									<option value="<?php echo $customer->id; ?>" <?php if($car_data->cus_id==$customer->id) echo "selected"; ?>><?php echo $customer->name; ?></option>
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
								<option value="<?php echo $i; ?>" <?php if($car_data->model==$i) echo "selected"; ?>><?php echo $i; ?></option>
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
									<option value="<?php echo $car_type->id; ?>" <?php if($car_type->id==$car_data->ct_id) echo "selected"; ?>><?php echo $car_type->name; ?></option>
								<?php } ?>
							</select>
							<div class="input-error"></div>
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="image"><?php echo lang('picture'); ?></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<input type="file" name="picture" />
							<?php if(!empty($car_data->image)) echo "<img src='".base_url()."uploads/cars/".$car_data->image."' class='img-thumbnail' style='max-width:100px;' />"; else echo "<small class='grey'>Not Uploaded</small>"; ?>
						</div>
					</div>
					
					<div class="col-sm-4 col-md-3 text-right">
						<div class="form-group">
							<label for="details"><?php echo lang('details'); ?></label>
						</div>
					</div>
					<div class="col-sm-7 col-md-8">
						<div class="form-group">
							<textarea name="details" class="form-control" placeholder="<?php echo lang('details'); ?>" required><?php echo $car_data->details; ?></textarea>
						</div>
					</div>
					<div class="col-md-12 submit text-center">
						<input type="submit" class="btn btn-primary" name="edit_car_btn" value="<?php echo lang('save'); ?>" />
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