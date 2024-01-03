        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><?php echo lang('invoices'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['invoice_new'])) { ?>
						<a href="<?php echo base_url(); ?>invoices/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('invoice'); ?></a>
						<?php } ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo lang('invoices'); ?></h1>
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
								<th class="text-center"><?php echo lang('invoice'); ?></th>
								<th class="text-center"><?php echo lang('check_out'); ?></th>
								<th class="text-center stage"><?php echo lang('stage'); ?></th>
								<th class="operations"></th>
							</tr>
							<?php if(!empty($invoices)) foreach($invoices as $invoice) {
								if($invoice->stage==1) { $stage=lang('new'); $stage_class="red-state"; }
								if($invoice->stage==2) { $stage=lang('in_progress'); $stage_class="yellow-state"; }
								if($invoice->stage==3) { $stage=lang('completed'); $stage_class="green-state"; }
								?>
							<tr>
								<td class="text-left"><a href="<?php echo base_url(); ?>invoices/view/<?php echo url_id_encode($invoice->id); ?>"><?php echo $invoice->car_id; ?></a></td>
								<td class="text-center"><?php echo date('j M, o g:i A',strtotime($invoice->invoice)); ?></td>
								<td class="text-center"><?php if(!empty($invoice->check_out)) echo date('j M, o g:i A',strtotime($invoice->check_out)); else echo lang('not_available'); ?></td>
								<td class="text-center <?php echo $stage_class; ?>"><?php echo $stage; ?></td>
								<td>
								<?php if(isset($permission_data['invoice_edit'])) { ?>
								<a href="<?php echo base_url(); ?>invoices/edit/<?php echo url_id_encode($invoice->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
								<?php } if(isset($permission_data['invoice_delete'])) { ?>
								<a href="<?php echo base_url(); ?>invoices/delete/<?php echo url_id_encode($invoice->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a>
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