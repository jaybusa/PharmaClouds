        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><?php echo lang('customer_types'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['customer_type_new'])) { ?>
						<a href="<?php echo base_url(); ?>customer-types/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('customer_type'); ?></a>
						<?php } ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo lang('customer_types'); ?></h1>
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
								<th class="operations"></th>
							</tr>
							<?php if(!empty($customer_types)) foreach($customer_types as $customer_type) {
								if($customer_type->status==0) {
									$state_class="inactive-state";
								} elseif($customer_type->status==1) {
									$state_class="active-state";
								}
								?>
							<tr>
								<td class="<?php echo $state_class; ?>"><i class="fa fa-circle"></i></td>
								<td class="text-left"><?php echo $customer_type->name; ?></td>
								<td>
								<?php if(isset($permission_data['customer_type_edit'])) { ?>
								<a href="<?php echo base_url(); ?>customer-types/edit/<?php echo url_id_encode($customer_type->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
								<?php } if(isset($permission_data['customer_type_delete'])) { ?>
								<a href="<?php echo base_url(); ?>customer-types/delete/<?php echo url_id_encode($customer_type->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a>
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