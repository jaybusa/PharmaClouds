        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><?php echo lang('car_types'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['car_type_new'])) { ?>
						<a href="<?php echo base_url(); ?>car-types/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('car_type'); ?></a>
						<?php } ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo lang('car_types'); ?></h1>
                    </div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?php if(!empty($_GET['errormsg'])) { echo "<div class='alert alert-danger'>".$_GET['errormsg']."</div>"; } ?>
						<?php if(!empty($_GET['successmsg'])) { echo "<div class='alert alert-success'>".$_GET['successmsg']."</div>"; } ?>
					</div>
				</div>
				<div class="row data">
					<div class="col-sm-12">
						<div class="information">
							
						</div>
						<table class="table table-striped">
							<tr>
								<th class="status"></th>
								<th class="text-left"><?php echo lang('name'); ?></th>
								<th class="text-center"><?php echo lang('picture'); ?></th>
								<th class="operations"></th>
							</tr>
							<?php if(!empty($car_types)) foreach($car_types as $car_type) {
								if($car_type->status==0) {
									$state_class="inactive-state";
								} elseif($car_type->status==1) {
									$state_class="active-state";
								}
								?>
							<tr>
								<td class="<?php echo $state_class; ?>"><i class="fa fa-circle"></i></td>
								<td class="text-left"><?php echo $car_type->name; ?></td>
								<td class="text-center">
									<?php if($car_type->picture!=NULL) { ?>
										<img src="<?php echo base_url(); ?>uploads/car_types/<?php echo $car_type->picture; ?>" style="width:30px;height:30px;" />
									<?php } else { ?>
										<?php echo lang('not_available'); ?>
									<?php } ?>
								</td>
								<td>
								<?php if(isset($permission_data['car_type_edit'])) { ?>
								<a href="<?php echo base_url(); ?>car-types/edit/<?php echo url_id_encode($car_type->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
								<?php } if(isset($permission_data['car_type_delete'])) { ?>
								<a href="<?php echo base_url(); ?>car-types/delete/<?php echo url_id_encode($car_type->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a>
								<?php } ?>
								</td>
							</tr>
							<?php } else { ?>
								<tr><td colspan="4"><span class="not-available"><?php echo lang('not_available'); ?></span></td></tr>
							<?php } ?>
						</table>
					</div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->