        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>check-ins/view/<?php echo url_id_encode($checkin_data->id);?>"><?php echo $checkin_data->car_id." ".lang('spare_parts'); ?></a></li>
							<li><?php echo $sparepart_data->name; ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						
					</div>
				</div>
				
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo $sparepart_data->name; ?></h1>
                    </div>
					<div class="row">
						<div class="col-sm-12">
							<?php if(!empty($alertMsg)) { echo $alertMsg; } ?>
						</div>
					</div>
					<div class="col-sm-6 view-data">
						<table class="table table-responsive">
							<tr>
								<th class="text-right"><?php echo lang('invoice_id'); ?></th><td class="text-left"><?php echo $sparepart_data->invoice_id; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('invoice_price'); ?></th><td class="text-left"><?php echo $sparepart_data->invoice_price." ".lang('sar_currency'); ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('hand_price'); ?></th><td class="text-left"><?php echo $sparepart_data->hand_price." ".lang('sar_currency'); ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('details'); ?></th><td class="text-left"><?php echo $sparepart_data->details; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('created_on'); ?></th><td class="text-left"><?php echo date('j M, o g:i A',strtotime($sparepart_data->createdon)); ?></td>
							</tr>
						</table>
					</div>
					<div class="col-sm-6 view-data">
						<strong>Invoice File:</strong><br />
						<?php 
							$filearr=explode('.',$sparepart_data->invoice_file);
							$ext=$filearr[(count($filearr)-1)];
							$imgArr=array('JPG','JPEG','jpg','jpeg','png','gif');
							$enc = mb_detect_encoding($sparepart_data->invoice_file);
							$invoice_file = mb_convert_encoding($sparepart_data->invoice_file, "ASCII", $enc);
							if(in_array($ext,$imgArr)) {
								?>
								<a href="<?php echo base_url();?>uploads/checkin/<?php echo $sparepart_data->cico_id;?>/<?php echo $sparepart_data->invoice_file; ?>" target="_blank"><img src="<?php echo base_url();?>uploads/checkin/<?php echo $sparepart_data->cico_id;?>/<?php echo $sparepart_data->invoice_file; ?>" class="img-responsive" /></a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>uploads/checkin/<?php echo $sparepart_data->cico_id;?>/<?php echo $invoice_file; ?>" target="_blank"><?php echo $invoice_file; ?></a>
							<?php }
						?>
					</div>
				</div>
				
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->