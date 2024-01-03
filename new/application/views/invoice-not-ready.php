		<!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links hidden-print">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>check-ins"><?php echo lang('check_ins'); ?></a></li>
							<li><a href="<?php echo base_url(); ?>check-ins/view/<?php echo url_id_encode($invoice_data->id);?>"><?php echo $invoice_data->car_id; ?></a></li>
							<li><?php echo lang('invoice_not_ready');?></li>
						</ul>
					</div>
				</div>

                <div class="row">
                    <div class="col-sm-12 invoice-data">
						<h2><?php echo lang('invoice_not_ready_msg'); ?></h2>
						
					</div>
				</div>
				
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->