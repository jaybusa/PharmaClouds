        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><?php echo lang('customers'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['customer_new'])) { ?>
						<a href="<?php echo base_url(); ?>customers/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('customer'); ?></a>
						<?php } ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo lang('customers'); ?></h1>
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
						<table class="table table-striped" id="customersList">
						  <thead>
							<tr>
								<th class="status"></th>
								<th class="text-left"><?php echo lang('name'); ?></th>
								<th class="text-center role"><?php echo lang('type'); ?></th>
								<th class="operations"></th>
								<th></th>
							</tr>
						  </thead>
						  <tbody>
							<?php if(!empty($customers)) foreach($customers as $customer) {
								if($customer->status==0) {
									$state_class="inactive-state";
								} elseif($customer->status==1) {
									$state_class="active-state";
								}
								?>
							<tr>
								<td class="<?php echo $state_class; ?>"><i class="fa fa-circle"></i></td>
								<td class="text-left"><a href="<?php echo base_url(); ?>customers/view/<?php echo url_id_encode($customer->id); ?>"><?php echo $customer->name; ?></a></td>
								<td class="text-center"><?php echo $customer->type; ?></td>
								<td>
								<?php if(isset($permission_data['customer_edit'])) { ?>
								<a href="<?php echo base_url(); ?>customers/edit/<?php echo url_id_encode($customer->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
								<?php } if(isset($permission_data['customer_delete'])) { ?>
								<a href="<?php echo base_url(); ?>customers/delete/<?php echo url_id_encode($customer->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a>
								<?php } ?>
								</td>
								<td><?php echo $customer->createdon; ?></td>
							</tr>
							<?php } else { ?>
								<tr><td colspan="4"><span class="not-available"><?php echo lang('not_available'); ?></span></td></tr>
							<?php } ?>
						  </tbody>
						</table>
					</div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->
	<script>
	$(document).ready(function() {
		<?php if($config['lang']=="ar") { ?>
			var langUrl="//cdn.datatables.net/plug-ins/1.10.13/i18n/Arabic.json";
		<?php } else { ?>
			var langUrl="";
		<?php } ?>
		$('#customersList').DataTable({
			"language": {
				"url": langUrl
			},
			"columnDefs": [
				{ "orderable": false, "targets": [0,3] },
				{ "targets": 4,
				"visible": false,
                "searchable": false}
			],
			"order": [[ 4, "desc" ]]
		});
	} );
	</script>