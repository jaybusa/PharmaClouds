        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><?php echo lang('spare_parts'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['sparepart_new'])) { ?>
						<a href="<?php echo base_url(); ?>spare-parts/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('spare_part'); ?></a>
						<?php } ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo lang('spare_parts'); ?></h1>
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
								<th class="status"><?php echo lang('status'); ?></th>
								<th class="text-left"><?php echo lang('name'); ?></th>
								<th class="text-center"><?php echo lang('price_invoice_hand'); ?></th>
								<th class="operations"></th>
							</tr>
							<?php if(!empty($spareparts)) foreach($spareparts as $sparepart) { 
								if($sparepart->status==0) {
									$state_class="inactive-state";
								} elseif($sparepart->status==1) {
									$state_class="active-state";
								}
								?>
							<tr>
								<td class="<?php echo $state_class; ?>"><i class="fa fa-circle"></i></td>
								<td class="text-left"><a href="<?php echo base_url(); ?>spare-parts/view/<?php echo url_id_encode($sparepart->id); ?>"><?php echo $sparepart->name; ?></a></td>
								<td class="text-center"><?php echo $sparepart->invoice_price." ".lang('sar_currency'); ?> / <?php echo $sparepart->hand_price." ".lang('sar_currency'); ?></td>
								<td>
								<?php if(isset($permission_data['sparepart_edit'])) { ?>
								<a href="<?php echo base_url(); ?>spare-parts/edit/<?php echo url_id_encode($sparepart->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
								<?php } if(isset($permission_data['sparepart_delete'])) { ?>
								<a href="<?php echo base_url(); ?>spare-parts/delete/<?php echo url_id_encode($sparepart->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a>
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