		<?php if(isset($permission_data['progress_stage']) || isset($permission_data['pending_stage']) || isset($permission_data['completed_state'])) { ?>
		<!-- Modal -->
		<div id="addSparePart" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title"><?php echo lang('add_spare_part'); ?></h3>
			  </div>
			  <div id="server_response"></div>
			  <form action="#" method="post" name="add_spare_part_form" id="add_spare_part_form" enctype="multipart/form-data">
			  <div class="modal-body row">
				<div class="col-sm-4 col-md-3 text-right">
					<div class="form-group">
						<label for="name"><?php echo lang('spare_part_name'); ?> <span class="required">*</span></label>
					</div>
				</div>
				<div class="col-sm-7 col-md-8">
					<div class="form-group">
						<input type="text" class="form-control" name="name" placeholder="<?php echo lang('name'); ?>" required />
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-4 col-md-3 text-right">
					<div class="form-group">
						<label for="invoice_id"><?php echo lang('invoice_id'); ?> <span class="required">*</span></label>
					</div>
				</div>
				<div class="col-sm-7 col-md-8">
					<div class="form-group">
						<input type="number" step="1" min="0" class="form-control" name="invoice_id" placeholder="<?php echo lang('invoice_id'); ?>" onkeyup="return validateInvoiceId();" required />
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-4 col-md-3 text-right">
					<div class="form-group">
						<label for="invoice_price"><?php echo lang('invoice_price'); ?> <span class="required">*</span></label>
					</div>
				</div>
				<div class="col-sm-7 col-md-8">
					<div class="form-group">
						<input type="number" step="0.01" min="0" class="form-control" name="invoice_price" placeholder="<?php echo lang('invoice_price'); ?>" onkeyup="return validateInvoicePrice();" required />
					</div>
				</div>
				<div class="clearfix"></div>
				
				<style>
					img.scanned {
						height: 200px; /** Sets the display size */
						margin-right: 12px;
					}
					div#images {
						margin-top: 20px;
					}
					.scan-btn { margin:5px;}
				</style>
				<div class="col-sm-4 col-md-3 text-right">
					<div class="form-group">
						<label for="invoice_file"><?php echo lang('invoice_file'); ?></label>
					</div>
				</div>
				<div class="col-sm-7 col-md-8">
					<input type="hidden" name="scanned_file_name" id="scanned_file_name" value="" />
					<div class="form-group" id="fileUploadScan">
						<input type="file" class="form-control" name="invoice_file" />
						
						<a class="btn btn-info btn-sm scan-btn" onclick="scanAndUploadDirectly('jpg');"><?php echo lang('scan_to_jpg'); ?></a>
						<a class="btn btn-info btn-sm scan-btn" onclick="scanAndUploadDirectly('pdf');"><?php echo lang('scan_to_pdf'); ?></a>
						
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-4 col-md-3 text-right">
					<div class="form-group">
						<label for="hand_price"><?php echo lang('hand_price'); ?> <span class="required">*</span></label>
					</div>
				</div>
				<div class="col-sm-7 col-md-8">
					<div class="form-group">
						<input  type="number" step="0.01" min="0" class="form-control" name="hand_price" placeholder="<?php echo lang('hand_price'); ?>" onkeyup="return validateHandPrice();" required />
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-4 col-md-3 text-right">
					<div class="form-group">
						<label for="details"><?php echo lang('details'); ?> <span class="required">*</span></label>
					</div>
				</div>
				<div class="col-sm-7 col-md-8">
					<div class="form-group">
						<textarea class="form-control" name="details" placeholder="<?php echo lang('details'); ?>" required></textarea>
					</div>
				</div>
			  </div>
			  <div class="clearfix"></div>
			  <div class="modal-footer">
				<input type="submit" class="btn btn-primary" name="add_spare_part_btn" id="add_spare_part_btn" value="<?php echo lang('add'); ?>" />
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
				<script>
				function scanAndUploadDirectly(format) {
					scanner.scan(displayServerResponse,
					{
						"output_settings": [
						{
							"type": "upload",
							"format": format,
							"upload_target": {
								"url": "<?php echo base_url(); ?>check-ins/process-sparepart/<?php echo url_id_encode($check_in_data->id); ?>",
								"post_fields": {
									"sample-field": "Test scan"
								},
								"cookies": document.cookie,
								"headers": [
								"Referer: " + window.location.href,
								"User-Agent: " + navigator.userAgent
								]
							}
						}
						]
					}
					);
				}
				function displayServerResponse(successful, mesg, response) {
					if(!successful) { // On error
						$('#server_response').html('<div class="alert alert-danger">Failed: ' + mesg + "</div>");
						return;
					}
					if(successful && mesg != null && mesg.toLowerCase().indexOf('user cancel') >= 0) { // User cancelled.
						$('#server_response').html('<div class="alert alert-danger">User Cancelled</div>');
						return;
					}
					
					
					
					$('#server_response').html('<div class="alert alert-success">Scanned File Uploaded successfully.');
					var files=String(scanner.getUploadResponse(response)).split('\\n');
					$('#fileUploadScan').html( files[0] );
					$('#scanned_file_name').val( files[0] );
					
				}
				</script>
			  </div>
			  </form>
			</div>

		  </div>
		</div>
		
		<!-- Modal -->
		<div id="updateDiscount" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo lang('update_discount'); ?></h4>
			  </div>
			  <form action="#" method="post" name="update_discount_form">
			  <div class="modal-body row">
				<div class="col-sm-4 col-md-3 text-right">
					<div class="form-group">
						<label for="discount"><?php echo lang('discount'); ?> <span class="required">*</span></label>
					</div>
				</div>
				<div class="col-sm-7 col-md-8">
					<div class="form-group">
						<input type="number" step=1 min=0 max=99  class="form-control" name="discount" placeholder="<?php echo lang('discount'); ?>"  onkeyup="return validateDiscount();" value="<?php echo $check_in_data->discount; ?>" required />
					</div>
				</div>
			  <div class="clearfix"></div>
			  </div>
			  <div class="modal-footer">
				<input type="submit" class="btn btn-primary" name="update_discount_btn" id="add_spare_part_btn" value="Add" />
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
			  </div>
			  </form>
			</div>

		  </div>
		</div>
		<?php } ?>
		
		<?php if(($check_in_data->stage==1 || $check_in_data->stage==2) && isset($permission_data['check_in_files'])) { ?>
		<!-- Modal -->
		<div id="addCarImage" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title"><?php echo lang('add_car_images'); ?></h3>
			  </div>
			  <form action="#" method="post" name="add_car_image_form" enctype="multipart/form-data">
			  <div class="modal-body row">
				<div class="col-sm-12">
					<?php echo lang('number_of_files'); ?> <input type="number" min=1 step=1 value="0" name="nof" id="nof" class="form-control" onchange="multipleFiles()" onkeyup="multipleFiles()" style="display:inline-block;width:80px;" /><br /><br />
				</div>
				<div class="col-sm-12">
					<div id="multiple_files">
					</div>
				</div>
			  </div>
			  <div class="modal-footer">
				<input type="submit" class="btn btn-primary" name="add_car_image_btn" id="add_car_image_btn" value="Add" />
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
			  </div>
			  </form>
			</div>

		  </div>
		</div>
		<?php } ?>
		
		<?php if(isset($permission_data['progress_stage']) || isset($permission_data['pending_stage']) || isset($permission_data['completed_state'])) { ?>
		<?php
			$net_invoice_price=$price->net_invoice_price;
			$net_hand_price=$price->net_hand_price;
			$net_price=$net_invoice_price+$net_hand_price;
			$discount=$check_in_data->discount;
			$discounted_value=round(($net_price*($discount)/100),2);
			$discounted_price=$net_price-$discounted_value;
			$paid=$check_in_data->paid;
			$unpaid=$discounted_price-$paid;
		?>
		<?php } ?>
		
		<!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><a href="<?php echo base_url(); ?>check-ins"><?php echo lang('check_ins'); ?></a></li>
							<li><?php echo $check_in_data->car_id; ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($permission_data['check_in_new'])) { ?>
						<a href="<?php echo base_url(); ?>check-ins/add" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> <?php echo lang('new')." ".lang('check_in'); ?></a>
						<?php } ?>
						<a href="<?php echo base_url(); ?>check-ins" class="btn btn-default btn-sm"><i class="fa fa-list"></i> <?php echo lang('list_check_ins'); ?></a>
						<a href="<?php echo base_url(); ?>check-ins/view-pdf/<?php echo url_id_encode($check_in_data->id); ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-file-pdf-o"></i> <?php echo lang('pdf'); ?></a>
					</div>
				</div>
				
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo $check_in_data->car_id; ?></h1>
                    </div>
					<div class="row">
						<div class="col-sm-12">
							
							<?php if(!empty($alertMsg)) { foreach($alertMsg as $msg) echo $msg; } ?>
							<?php if(!empty($_GET['errormsg'])) { echo "<div class='alert alert-danger'>".$_GET['errormsg']."</div>"; } ?>
							<?php if(!empty($_GET['successmsg'])) { echo "<div class='alert alert-success'>".$_GET['successmsg']."</div>"; } ?>
						</div>
					</div>
					<div class="col-sm-12 view-data">
						<div class="col-md-6">
						<?php
						if($check_in_data->stage==1) { $stage_name=lang('new'); $stage_class="red-state";}
						if($check_in_data->stage==2) { $stage_name=lang('in_progress');  $stage_class="yellow-state";}
						if($check_in_data->stage==3) { $stage_name=lang('pending');  $stage_class="green-state";}
						if($check_in_data->stage==4) { $stage_name=lang('completed');  $stage_class="green-state";}
						?>
						<table class="table table-responsive">
							<tr>
								<th class="text-right"><?php echo lang('stage'); ?></th><td class="text-left <?php echo $stage_class; ?>"><?php echo $stage_name; ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('check_in_date'); ?></th><td class="text-left"><?php if(!empty($check_in_data->check_in)) echo date('j M, o g:i A',strtotime($check_in_data->check_in)); else echo lang('not_updated_yet'); ?></td>
							</tr>
							<tr>
								<th class="text-right"><?php echo lang('check_out_date'); ?></th><td class="text-left"><?php if(!empty($check_in_data->check_out)) echo date('j M, o g:i A',strtotime($check_in_data->check_out)); else echo lang('not_updated_yet'); ?></td>
							</tr>
							<tr>
								<th colspan="2">
									
									<?php if($check_in_data->stage==1 && isset($permission_data['new_progress'])) { ?>
									<form action="#" method="post" name="stateChangeForm">
										<input type="hidden" name="cc_id" value="<?php echo $check_in_data->id; ?>" />
										<input type="submit" class="btn btn-primary" name="state_in_progress" value="<?php echo lang('make_in_progress'); ?>" />
									</form>
									<?php } elseif($check_in_data->stage==2 && isset($permission_data['progress_pending'])) { ?>
										<button class="btn btn-primary" data-toggle="modal" data-target="#checkOut"><?php echo lang('check_out'); ?></button>
									<?php } elseif($check_in_data->stage==3) {
										if(isset($permission_data['pending_completed'])) {
											if($discounted_price <= $check_in_data->paid) { ?>
												<form action="#" method="post" name="stateCompleteForm" style="display:inline;">
													<input type="hidden" name="cc_id" value="<?php echo $check_in_data->id; ?>" />
													<input type="submit" class="btn btn-primary" name="state_complete" value="<?php echo lang('complete'); ?>" />
												</form>
											<?php } else { ?>
												<button class="btn btn-primary" data-toggle="modal" data-target="#updatePayment"><?php echo lang('update_payment'); ?></button>
											<?php } ?>
										<?php } ?>
										<a href="<?php echo base_url(); ?>invoices/view/<?php echo url_id_encode($check_in_data->id);?>" class="btn btn-primary"><?php echo lang('view_invoice'); ?></a>
									<?php } elseif($check_in_data->stage==4) { ?>
										<a href="<?php echo base_url(); ?>invoices/view/<?php echo url_id_encode($check_in_data->id);?>" class="btn btn-primary"><?php echo lang('view_invoice'); ?></a>
									<?php } ?>
								</th>
							</tr>
						</table>
						
						</div>
						<div class="col-md-6">
							<table class="table table-responsive">
							<?php if(!empty($check_in_data->last_modified_on)) { ?>
							<tr>
								<th class="text-right"><?php echo lang('last_modified_on'); ?></th><td class="text-left"><?php echo date('j M, o g:i A',strtotime($check_in_data->last_modified_on)); ?></td>
							</tr>
							<?php } if(!empty($check_in_data->last_modified_by)) { ?>
							<tr>
								<th class="text-right"><?php echo lang('last_modified_by'); ?></th><td class="text-left"><a href="<?php echo base_url(); ?>users/view/<?php echo $check_in_data->last_modified_by_user; ?>"><?php echo $check_in_data->last_modified_by; ?></a></td>
							</tr>
							<?php } ?>
							</table>
						</div>
					</div>
				</div>
				<hr />
				<?php if(isset($permission_data['progress_stage']) || isset($permission_data['pending_stage']) || isset($permission_data['completed_state'])) { ?>
				<div class="row">
					<div class="col-md-4">
						<div class="well pricing-aggr" id="priceAggr1">
						  <div class="text-primary small-show">
							<span class="label label-primary pull-right"><?php echo $net_invoice_price; ?> SAR</span> <small><?php echo lang('net_invoice_price'); ?></small>
						  </div>
						  <div class="text-primary small-show">
							<span class="label label-primary pull-right"><?php echo $net_hand_price; ?> SAR</span> <small><?php echo lang('net_hand_price'); ?></small>
						  </div>
						  <div class="text-success small-show">
							<span class="label label-success pull-right"><?php echo $net_price; ?> SAR</span> <small><?php echo lang('net_price'); ?></small>
						  </div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="well pricing-aggr" id="priceAggr2">
							<?php if($check_in_data->stage!=4) { ?>
							<button class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#updateDiscount"><?php echo lang('update_discount'); ?></button>
							<?php } ?>
							<div class="clearfix"></div>
						  <div class="text-primary small-show"><span class="label label-success pull-right"><?php echo $discount; ?> %</span> <small><?php echo lang('discount'); ?></small> </div>
						  
						</div>
					</div>
					<div class="col-md-4">
						<div class="well pricing-aggr" id="priceAggr3">
							<div class="text-success small-show">
								<span class="label label-danger pull-right"><?php echo $discounted_value; ?> SAR</span> <small><?php echo lang('discounted'); ?></small>
							</div>
							<div class="text-success big-show"><span class="label label-success pull-right"><?php echo $discounted_price; ?> SAR</span> <?php echo lang('total_price'); ?> </div>
							<div class="text-success small-show">
								<span class="label label-success pull-right"><?php echo $paid; ?> SAR</span> <small><?php echo lang('paid'); ?></small>
							</div>
							<div class="text-danger small-show">
								<?php if($unpaid >= 0) { ?>
									<span class="label label-danger pull-right"><?php echo $unpaid; ?> SAR</span> <small><?php echo lang('unpaid'); ?></small>
								<?php } else { ?>
									<span class="label label-success pull-right"><?php echo abs($unpaid); ?> SAR</span> <small><?php echo lang('extra_paid'); ?></small>
								<?php } ?>
							</div>
						</div>
					</div>
				</div><!--/row-->    
				
				<hr />
				<?php } ?>
				
				<div class="row">
					<?php if(isset($permission_data['spareparts'])) { ?>
					<div class="col-md-6">
						<div class="row">
							<div class="col-lg-12">
								<h4><?php echo lang('spare_parts'); ?>
								<?php if($check_in_data->stage==2) { ?>
								<button type="button" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#addSparePart"><i class="fa fa-plus"></i> <?php echo lang('add_spare_part'); ?></button>
								<?php } ?>
								</h4>
							</div>
							<div class="col-sm-12 data">
								<table class="table table-responsive table-striped">
									<tr>
										<th class="text-center"><?php echo lang('name'); ?></th>
										<th class="text-center"><?php echo lang('hand_price'); ?></th>
										<th class="text-center"><?php echo lang('invoice_price'); ?></th>
										<th class="text-center"><?php echo lang('invoice_id'); ?></th>
										<?php if($check_in_data->stage==2) { ?>
										<th class="operations"></th>
										<?php } ?>
									</tr>
									<?php if(!empty($spare_parts)) foreach($spare_parts as $sparepart) { ?>
									<tr>
										<td><a href="<?php echo base_url(); ?>spare-parts/view/<?php echo url_id_encode($sparepart->id); ?>"><?php echo $sparepart->name; ?></a></td>
										<td><?php echo $sparepart->hand_price." ".lang('sar_currency'); ?></td>
										<td><?php echo $sparepart->invoice_price." ".lang('sar_currency'); ?></td>
										<td><?php echo $sparepart->invoice_id; ?></td>
										<?php if($check_in_data->stage==2) { ?>
										<td>
										<?php if(isset($permission_data['sparepart_edit'])) { ?>
										<a href="<?php echo base_url(); ?>spare-parts/edit/<?php echo url_id_encode($sparepart->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
										<?php } if(isset($permission_data['sparepart_delete'])) { ?>
										<a href="<?php echo base_url(); ?>spare-parts/delete/<?php echo url_id_encode($sparepart->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a>
										<?php } ?>
										</td>
										<?php } ?>
									</tr>
									<?php } else { ?>
										<tr><td colspan="6"><span class="not-available">Not Available</span></td></tr>
									<?php } ?>
								</table>
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="col-md-6">
						<div class="row">
							<div class="col-lg-12">
								<h4><?php echo lang('check_in_files'); ?>
								<?php if(($check_in_data->stage==1 || $check_in_data->stage==2) && isset($permission_data['check_in_files'])) { ?>
								<button type="button" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#addCarImage"><i class="fa fa-plus"></i> <?php echo lang('add_car_images'); ?></button>
								<?php } ?>
								</h4>
							</div>
							<div class="col-sm-12 data">
								<?php $dirname="uploads/checkin/".$check_in_data->id."/images/";
								$images=glob($dirname."*.{png,gif,jpg,jpeg,JPG,JPEG}",GLOB_BRACE);
								$i=0;
								foreach($images as $image) { ?>
									<div class="col-sm-6 col-md-4 col-lg-3 text-center" style="border:solid thin #ccc;padding:3px;">
										<a href="<?php echo base_url().$image; ?>" target="_blank"><img src="<?php echo base_url().$image; ?>" class="img-responsive" /></a>
										<?php if($check_in_data->stage==1 || $check_in_data->stage==2) { ?>
										<a type="button" class="btn btn-danger btn-xs" id="<?php echo "image".$i;?>" data-id="<?php echo url_id_encode($image); ?>" onclick="return deleteCarImage($(this).attr('id'),$(this).attr('data-id'));">Delete</a>
										<?php } ?>
									</div>
								<?php $i++;
								if($i%2==0) echo "<div class='clearfix visible-sm'></div>";
								if($i%3==0) echo "<div class='clearfix visible-md'></div>";
								if($i%4==0) echo "<div class='clearfix visible-lg'></div>";
								} ?>
							</div>
						</div>
					</div>
				</div>
				
				<?php if(isset($permission_data['progress_stage']) || isset($permission_data['pending_stage']) || isset($permission_data['completed_state'])) { ?>
				<!-- Modal -->
				<div id="checkOut" class="modal fade" role="dialog">
				  <div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><?php echo lang('check_out'); ?></h4>
					  </div>
					  <form action="#" method="post" name="check_out_form">
					  <div class="modal-body row">
						<div class="col-sm-4 col-md-3 text-right">
							<div class="form-group">
								<label for="check_out"><?php echo lang('check_out'); ?> <span class="required">*</span></label>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="form-group">
								<input type="text" class="form-control" name="check_out" id="check_out" placeholder="<?php echo lang('check_out'); ?>"  value="<?php echo $check_in_data->check_out; ?>" required />
							</div>
							<script type="text/javascript">
							$(function () {
								$('#check_out').datetimepicker();
							});
						</script>
						</div>
						<div class="clearfix"></div>
						<div class="col-sm-4 col-md-3 text-right">
							<div class="form-group">
								<label for="paid"><?php echo lang('paid'); ?> <span class="required">*</span></label>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="form-group">
								<input type="number" step="0.01" min="0" class="form-control" name="paid" placeholder="<?php echo lang('paid'); ?>" onkeyup="return validatePaidPrice();" required />
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-sm-4 col-md-3 text-right">
							<div class="form-group">
								<label><?php echo lang('total_price'); ?> <span class="required">*</span></label>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="form-group">
								<div id="totalPrice"><?php echo $discounted_price." ".lang('sar_currency'); ?></div>
							</div>
						</div>
					  <div class="clearfix"></div>
					  </div>
					  <div class="modal-footer">
						<input type="submit" class="btn btn-primary" name="checkout_btn" id="checkout_btn" value="<?php echo lang('check_out');?>" />
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
					  </div>
					  </form>
					</div>

				  </div>
				</div>
				
				<!-- Modal -->
				<div id="updatePayment" class="modal fade" role="dialog">
				  <div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><?php echo lang('update_payment'); ?></h4>
					  </div>
					  <form action="#" method="post" name="update_payment_form">
					  <div class="modal-body row">
						
						<div class="col-sm-4 col-md-3 text-right">
							<div class="form-group">
								<label for="upaid"><?php echo lang('paid'); ?> <span class="required">*</span></label>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="form-group">
								<input type="number" step="0.01" min="0" class="form-control" name="upaid" placeholder="<?php echo lang('paid'); ?>" onkeyup="return validateUPaidPrice();" value="<?php echo $check_in_data->paid; ?>" required />
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-sm-4 col-md-3 text-right">
							<div class="form-group">
								<label><?php echo lang('total_price'); ?> <span class="required">*</span></label>
							</div>
						</div>
						<div class="col-sm-7 col-md-8">
							<div class="form-group">
								<div id="totalPrice"><?php echo $discounted_price." ".lang('sar_currency'); ?></div>
							</div>
						</div>
					  <div class="clearfix"></div>
						
					  </div>
					  <div class="modal-footer">
						<input type="submit" class="btn btn-primary" name="update_payment_btn" id="update_payment_btn" value="<?php echo lang('update');?>" />
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close'); ?></button>
					  </div>
					  </form>
					</div>

				  </div>
				</div>
				<?php } ?>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->
<script>
$(document).ready(function() {
	var aggr1Hgt=$('#priceAggr1').height();
	var aggr2Hgt=$('#priceAggr2').height();
	var aggr3Hgt=$('#priceAggr3').height();
	var aggrMax=Math.max(aggr1Hgt,aggr2Hgt,aggr3Hgt);
	$('#priceAggr1').height(aggrMax);
	$('#priceAggr2').height(aggrMax);
	$('#priceAggr3').height(aggrMax);
});
function validateHandPrice() {
	var ele=$('input[name=hand_price]');
	if(!($.isNumeric(ele.val()))) {
		ele.val('');
	} else {
		if(ele.val()<0) {
			ele.val('');
		}
	}
}
function validateInvoicePrice() {
	var ele=$('input[name=invoice_price]');
	if(!($.isNumeric(ele.val()))) {
		ele.val('');
	} else {
		if(ele.val()<0) {
			ele.val('');
		}
	}
}
function validateInvoiceId() {
	var ele=$('input[name=invoice_id]');
	if(!($.isNumeric(ele.val()))) {
		ele.val('');
	} else {
		if(ele.val()<0) {
			ele.val('');
		}
	}
}
function multipleFiles(){
	var num=$('#nof').val();
	if(!($.isNumeric(num))) {
		$('#nof').val(0);
	} else if(num<0) {
		$('#nof').val(0);
	}
	else if(num>20) {
		$("#multiple_files").html('<span class="red"><?php echo lang('maximum_20_files_error'); ?></span>');
	} else {
		var input_file="";
		for(var i=0; i < num; i++) {
			input_file += '<div class="col-md-6"><input type="file" name="car_image[]" /></div>';
		}
		$("#multiple_files").html(input_file);
	}
}
function validateDiscount() {
	var ele=$('input[name=discount]');
	if(!($.isNumeric(ele.val()))) {
		ele.val('');
	} else {
		if(ele.val()<0 || ele.val()>100) {
			ele.val(0);
		}
	}
}
function deleteCarImage(id,path) {
	if(confirm('Do you really want to delete?')) {
		$.post("<?php echo base_url();?>check-ins/delete-image",{file : path}, function(data){
			$('a#'+id).parent().remove();
		});
	} else { return false; }
}
function validatePaidPrice() {
	var ele=$('input[name=paid]');
	if(!($.isNumeric(ele.val()))) {
		ele.val('');
	} else {
		if(ele.val()<0) {
			ele.val('');
		}
	}
}
function validateUPaidPrice() {
	var ele=$('input[name=upaid]');
	if(!($.isNumeric(ele.val()))) {
		ele.val('');
	} else {
		if(ele.val()<0) {
			ele.val('');
		}
	}
}
</script>