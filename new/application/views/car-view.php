        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links hidden-print">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>cars"><?php echo lang('cars'); ?></a></li>
							<li><?php echo $car_data->car_id; ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['car_new'])) { ?>
							<?php if(isset($permission_data['check_in_new'])) { ?>
								<a href="<?php echo base_url(); ?>check-ins/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('check_in'); ?></a>
							<?php } ?>
						<a href="<?php echo base_url(); ?>cars/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('car'); ?></a>
						<?php } ?>
						<a href="<?php echo base_url(); ?>cars" class="btn btn-default btn-sm"><i class="fa fa-list"></i> <?php echo lang('list_cars'); ?></a>
						<a onclick="javascript:window.print();return false;" href="#" class="btn btn-default btn-sm"><i class="fa fa-print"></i> <?php echo lang('print'); ?></a>
					</div>
				</div>
				
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo $car_data->car_id; ?></h1>
                    </div>
					<div class="row">
						<div class="col-sm-12">
							<?php if(!empty($alertMsg)) { echo $alertMsg; } ?>
						</div>
					</div>
					<div class="col-sm-4 col-md-3">
						<?php if(!empty($car_data->image)) {
							$image=base_url()."uploads/cars/".$car_data->image;
						} else {
							$image=base_url()."uploads/default-car.png";
						} ?>
						<img src="<?php echo $image; ?>" class="img-responsive" />
					</div>
					<div class="col-sm-8 col-md-9 view-data">
						<table class="table table-responsive">
							<tr>
								<th class="text-right"><?php echo lang('owner'); ?></th><td class="text-left"><a href="<?php echo base_url(); ?>customers/view/<?php echo url_id_encode($car_data->cus_id); ?>"><?php echo $car_data->owner; ?></a></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('model'); ?></th><td class="text-left"><?php echo $car_data->model; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('type'); ?></th><td class="text-left">
									<?php if($car_data->ct_pic!=NULL) { ?>
										<img src="<?php echo base_url(); ?>uploads/car_types/<?php echo $car_data->ct_pic; ?>" style="width:30px;height:30px;" /> <?php echo $car_data->type; ?>
									<?php } else { ?>
										<?php echo lang('not_available'); ?>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('details'); ?></th><td class="text-left"><?php echo $car_data->details; ?></td>
							</tr>
							<?php if(!empty($car_data->last_modified_on)) { ?>
							<tr>
								<th class="text-right"><?php echo lang('last_modified_on'); ?></th><td class="text-left"><?php echo date('j M, o g:i A',strtotime($car_data->last_modified_on)); ?></td>
							</tr>
							<?php } if(!empty($car_data->last_modified_by)) { ?>
							<tr>
								<th class="text-right"><?php echo lang('last_modified_by'); ?></th><td class="text-left"><a href="<?php echo base_url(); ?>users/view/<?php echo $car_data->last_modified_by_user; ?>"><?php echo $car_data->last_modified_by; ?></a></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
				
				<div class="row data">
					<div class="col-sm-12">
						<h4><?php echo lang('check_ins'); ?></h4>
						<table class="table table-striped">
							<tr>
								<th class="text-left"><?php echo lang('car_id'); ?></th>
								<th class="text-center"><?php echo lang('check_in'); ?></th>
								<th class="text-center"><?php echo lang('check_out'); ?></th>
								<th class="text-center stage"><?php echo lang('stage'); ?></th>
								<th class="operations hidden-print"></th>
							</tr>
							<?php if(!empty($car_checkin_data)) foreach($car_checkin_data as $check_in) {
								if($check_in->stage==1) { $stage=lang('new'); $stage_class="red-state"; }
								if($check_in->stage==2) { $stage=lang('in_progress'); $stage_class="yellow-state"; }
								if($check_in->stage==3) { $stage=lang('pending'); $stage_class="green-state"; }
								if($check_in->stage==4) { $stage=lang('completed'); $stage_class="green-state"; }
								?>
							<tr>
								<td class="text-left"><a href="<?php echo base_url(); ?>check-ins/view/<?php echo url_id_encode($check_in->id); ?>"><?php echo $check_in->car_id; ?></a></td>
								<td class="text-center"><?php echo date('j M, o g:i A',strtotime($check_in->check_in)); ?></td>
								<td class="text-center"><?php if(!empty($check_in->check_out)) echo date('j M, o g:i A',strtotime($check_in->check_out)); else echo lang('not_available'); ?></td>
								<td class="text-center <?php echo $stage_class; ?>"><?php echo $stage; ?></td>
								<td class="hidden-print">
								<?php if(isset($permission_data['car_edit'])) { ?>
								<a href="<?php echo base_url(); ?>check-ins/edit/<?php echo url_id_encode($check_in->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
								<?php } if(isset($permission_data['car_delete'])) { ?>
								<a href="<?php echo base_url(); ?>check-ins/delete/<?php echo url_id_encode($check_in->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a>
								<?php } ?>
								</td>
							</tr>
							<?php } else { ?>
								<tr><td colspan="5"><span class="not-available"><?php echo lang('not_available'); ?></span></td></tr>
							<?php } ?>
						</table>
					</div>
                </div>
				
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->