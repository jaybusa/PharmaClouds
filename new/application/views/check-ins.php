        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><?php echo lang('check_ins'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['check_in_new'])) { ?>
						<a href="<?php echo base_url(); ?>check-ins/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('check_in'); ?></a>
						<?php } ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo lang('check_ins'); ?></h1>
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
								<th class="text-left"><?php echo lang('car_id'); ?></th>
								<th class="text-center"><?php echo lang('check_in'); ?></th>
								<th class="text-center"><?php echo lang('check_out'); ?></th>
								<th class="text-center stage"><?php echo lang('stage'); ?></th>
								<th class="operations"></th>
							</tr>
							<?php if(!empty($check_ins)) foreach($check_ins as $check_in) {
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
								<td>
								<?php if(isset($permission_data['check_in_edit'])) {
									if($check_in->stage!=4 || ($check_in->stage==4 && isset($permission_data['check_in_completed_edit_delete']))) { ?>
										<a href="<?php echo base_url(); ?>check-ins/edit/<?php echo url_id_encode($check_in->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
									<?php } ?>
								<?php } if(isset($permission_data['check_in_delete'])) {
									if($check_in->stage!=4 || ($check_in->stage==4 && isset($permission_data['check_in_completed_edit_delete']))) { ?>
										<a href="<?php echo base_url(); ?>check-ins/delete/<?php echo url_id_encode($check_in->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a>
									<?php } ?>
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