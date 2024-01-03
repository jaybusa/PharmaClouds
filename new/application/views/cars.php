        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><?php echo lang('cars'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['car_new'])) { ?>
						<a href="<?php echo base_url(); ?>cars/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('car'); ?></a>
						<?php } ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo lang('cars'); ?></h1>
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
						<table class="table table-striped" id="carsList">
						  <thead>
							<tr>
								<th class="text-left"><?php echo lang('car_id'); ?></th>
								<th class="text-center" style="width:80px;"><?php echo lang('type'); ?></th>
								<th class="text-center" style="width:100px;"><?php echo lang('model'); ?></th>
								<th class="operations"></th>
								<th></th>
							</tr>
						  </thead>
						  <tbody>
							<?php if(!empty($cars)) foreach($cars as $car) { ?>
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
								<td>
								<?php if(isset($permission_data['car_edit'])) { ?>
								<a href="<?php echo base_url(); ?>cars/edit/<?php echo url_id_encode($car->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
								<?php } if(isset($permission_data['car_delete'])) { ?>
								<a href="<?php echo base_url(); ?>cars/delete/<?php echo url_id_encode($car->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a>
								<?php } ?>
								</td>
								<td><?php echo $car->createdon; ?></td>
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
		$('#carsList').DataTable({
			"language": {
				"url": langUrl
			},
			"columnDefs": [
				{ "orderable": false, "targets": [1,3] },
				{ "searchable": false, "targets": [1,3] },
				{ "visible": false, "searchable": false, "targets": 4 },
			],
			"order": [[ 4, "desc" ]]
		});
	} );
	</script>