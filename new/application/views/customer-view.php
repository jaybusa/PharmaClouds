        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links hidden-print">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>customers"><?php echo lang('customers'); ?></a></li>
							<li><?php echo $customer_data->name; ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['customer_new'])) { ?>
						<a href="<?php echo base_url(); ?>customers/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('customer'); ?></a>
						<?php } ?>
						<a href="<?php echo base_url(); ?>customers" class="btn btn-default btn-sm"><i class="fa fa-list"></i> <?php echo lang('list_customers'); ?></a>
						<a onclick="javascript:window.print();return false;" href="#" class="btn btn-default btn-sm"><i class="fa fa-print"></i> <?php echo lang('print'); ?></a>
					</div>
				</div>
				
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo $customer_data->name; ?> <small><?php echo $customer_data->type; ?></small></h1>
                    </div>
					<div class="row">
						<div class="col-sm-12">
							<?php if(!empty($alertMsg)) { echo $alertMsg; } ?>
						</div>
					</div>
					<div class="col-sm-12 view-data">
						<table class="table table-responsive">
							<tr>
								<th class="text-right"><?php echo lang('email'); ?></th><td class="text-left"><?php echo $customer_data->email; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('phone'); ?></th><td class="text-left"><?php echo $customer_data->phone; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('status'); ?></th><td class="<?php echo $customer_data->state_class; ?> text-left"><i class="fa fa-circle"></i> <?php echo $customer_data->state_name; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('registered_on'); ?></th><td class="text-left"><?php echo date('j M, o g:i A',strtotime($customer_data->createdon)); ?></td>
							</tr>
							<?php if(!empty($customer_data->last_modified_on)) { ?>
							<tr>
								<th class="text-right"><?php echo lang('last_modified_on'); ?></th><td class="text-left"><?php echo date('j M, o g:i A',strtotime($customer_data->last_modified_on)); ?></td>
							</tr>
							<?php } if(!empty($customer_data->last_modified_by)) { ?>
							<tr>
								<th class="text-right"><?php echo lang('last_modified_by'); ?></th><td class="text-left"><a href="<?php echo base_url(); ?>users/view/<?php echo $customer_data->last_modified_by_user; ?>"><?php echo $customer_data->last_modified_by; ?></a></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<h4><?php echo lang('cars'); ?></h4>
						<table class="table table-striped">
							<tr>
								<th class="text-left"><?php echo lang('car_id'); ?></th>
								<th class="text-center"><?php echo lang('type'); ?></th>
								<th class="text-center"><?php echo lang('model'); ?></th>
								<th class="operations hidden-print"></th>
							</tr>
							<?php if(!empty($customer_cars)) foreach($customer_cars as $car) { ?>
							<tr>
								<td class="text-left"><a href="<?php echo base_url(); ?>cars/view/<?php echo url_id_encode($car->id); ?>"><?php echo $car->car_id; ?></a></td>
								<td class="text-center">
									<?php if($car->ct_pic!=NULL) { ?>
										<img src="<?php echo base_url(); ?>uploads/car_types/<?php echo $car->ct_pic; ?>" style="width:30px;height:30px;" title="<?php echo $car->type;?>" />
									<?php } else { ?>
										<?php echo lang('not_available'); ?>
									<?php } ?>
								</td>
								<td class="text-center"><?php echo $car->model; ?></td>
								<td class="hidden-print">
								<?php if(isset($permission_data['customer_edit'])) { ?>
								<a href="<?php echo base_url(); ?>cars/edit/<?php echo url_id_encode($car->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
								<?php } if(isset($permission_data['customer_delete'])) { ?>
								<a href="<?php echo base_url(); ?>cars/delete/<?php echo url_id_encode($car->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a>
								<?php } ?>
								</td>
							</tr>
							<?php } else { ?>
								<tr><td colspan="4" class="text-center"><span class="not-available"><?php echo lang('not_available'); ?></span></td></tr>
							<?php } ?>
						</table>
					</div>
				</div>
				
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->