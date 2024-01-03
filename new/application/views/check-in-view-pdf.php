<style>
<?php if($config['dir']=="rtl") { ?>
.text-left { text-align:right;}
.text-right { text-align:left;}
<?php } else { ?>
.text-left { text-align:left;}
.text-right { text-align:right;}
<?php } ?>
table tr td { margin:10px;}
</style>

<?php if(isset($permission_data['progress_stage']) || isset($permission_data['pending_stage']) || isset($permission_data['completed_state'])) { 
	$net_invoice_price=$price->net_invoice_price;
	if($net_invoice_price=="") $net_invoice_price=0;
	$net_hand_price=$price->net_hand_price;
	if($net_hand_price=="") $net_hand_price=0;
	$net_price=$net_invoice_price+$net_hand_price;
	if($net_price=="") $net_price=0;
	$discount=$check_in_data->discount;
	if($discount=="") $discount=0;
	$discounted_value=round(($net_price*($discount)/100),2);
	if($discounted_value=="") $discounted_value=0;
	$discounted_price=$net_price-$discounted_value;
	if($discounted_price=="") $discounted_price=0;
	$paid=$check_in_data->paid;
	if($paid=="") $paid=0;
	$unpaid=$discounted_price-$paid;
	if($unpaid=="") $unpaid=0;
} ?>
<table width="100%" cellpadding="5">
	<tr>
		<td colspan=2>
			<h1><?php echo $check_in_data->car_id; ?></h1>
		</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td width="50%">
			<?php
				if($check_in_data->stage==1) { $stage_name=lang('new'); $stage_class="red-state";}
				if($check_in_data->stage==2) { $stage_name=lang('in_progress');  $stage_class="yellow-state";}
				if($check_in_data->stage==3) { $stage_name=lang('pending');  $stage_class="green-state";}
				if($check_in_data->stage==4) { $stage_name=lang('completed');  $stage_class="green-state";}
			?>
			<table width="100%" cellpadding="5">
				<tr>
					<td><strong><?php echo lang('stage'); ?>: </strong><?php echo $stage_name; ?></td>
				</tr>
				<tr>
					<td><strong><?php echo lang('check_in_date'); ?>: </strong><br /><?php if(!empty($check_in_data->check_in)) echo date('j M, o g:i A',strtotime($check_in_data->check_in)); else echo lang('not_updated_yet'); ?></td>
				</tr>
				<tr>
					<td><strong><?php echo lang('check_out_date'); ?>: </strong><br /><?php if(!empty($check_in_data->check_out)) echo date('j M, o g:i A',strtotime($check_in_data->check_out)); else echo lang('not_updated_yet'); ?></td>
				</tr>
			</table>
		</td>
		<td width="50%">
			<table width="100%" cellpadding="5">
				<?php if(!empty($check_in_data->last_modified_on)) { ?>
				<tr>
					<td><strong><?php echo lang('last_modified_on'); ?>: </strong><br /><?php echo date('j M, o g:i A',strtotime($check_in_data->last_modified_on)); ?></td>
				</tr>
				<?php } if(!empty($check_in_data->last_modified_by)) { ?>
				<tr>
					<td><strong><?php echo lang('last_modified_by'); ?>: </strong><br /><?php echo $check_in_data->last_modified_by; ?></td>
				</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<?php if(isset($permission_data['progress_stage']) || isset($permission_data['pending_stage']) || isset($permission_data['completed_state'])) { ?>
	<tr>
		<td colspan="2">
			<table style="font-size:14px;">
				<tr>
					<td align="left">
						<table cellpadding="5">
							<tr>
								<td><?php echo lang('net_invoice_price'); ?>: <?php echo $net_invoice_price; ?> SAR</td>
							</tr>
							<tr>
								<td><?php echo lang('net_hand_price'); ?>: <?php echo $net_hand_price; ?> SAR</td>
							</tr>
							<tr>
								<td><?php echo lang('net_price'); ?>: <?php echo $net_price; ?> SAR</td>
							</tr>
						</table>
					</td>
					<td align="center">
						<table cellpadding="5">
							<tr>
								<td><?php echo lang('discount'); ?>: <?php echo $discount; ?> %</td>
							</tr>
						</table>
					</td>
					<td align="right">
						<table cellpadding="5">
							<tr>
								<td>
									<?php echo lang('discounted'); ?>: <?php echo $discounted_value; ?> SAR
								</td>
							</tr>
							<tr>
								<td>
									<?php echo lang('total_price'); ?>: <?php echo $discounted_price; ?> SAR
								</td>
							</tr>
							<tr>
								<td>
									<?php echo lang('paid'); ?>: <?php echo $paid; ?> SAR
								</td>
							</tr>
							<tr>
								<td>
									<?php if($unpaid >= 0) { ?>
										<?php echo lang('unpaid'); ?>: <?php echo $unpaid; ?> SAR
									<?php } else { ?>
										<?php echo lang('extra_paid'); ?>: <?php echo abs($unpaid); ?> SAR
									<?php } ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<?php } ?>
	
	<?php if(isset($permission_data['spareparts'])) { ?>
	<tr>
		<td colspan="2">
			<h4><?php echo lang('spare_parts'); ?></h4>
			<table cellpadding="5" border="1" style="font-size:14px;">
				<tr>
					<th><strong><?php echo lang('name'); ?></strong></th>
					<th><strong><?php echo lang('hand_price'); ?></strong></th>
					<th><strong><?php echo lang('invoice_price'); ?></strong></th>
					<th><strong><?php echo lang('invoice_id'); ?></strong></th>
				</tr>
				<?php if(!empty($spare_parts)) foreach($spare_parts as $sparepart) { ?>
				<tr>
					<td><?php echo $sparepart->name; ?></td>
					<td><?php echo $sparepart->hand_price." ".lang('sar_currency'); ?></td>
					<td><?php echo $sparepart->invoice_price." ".lang('sar_currency'); ?></td>
					<td><?php echo $sparepart->invoice_id; ?></td>
				</tr>
				<?php } else { ?>
				<tr><td colspan="4" align="center">Not Available</td></tr>
				<?php } ?>
			</table>
		</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<?php } ?>
	<tr>
		<td colspan="2">
			<h4><?php echo lang('check_in_files'); ?></h4>
			<?php $dirname="uploads/checkin/".$check_in_data->id."/images/";
			$images=glob($dirname."*.{png,gif,jpg,jpeg,JPG,JPEG}",GLOB_BRACE);
			$i=0;
			foreach($images as $image) { ?>
				&nbsp;<img src="<?php echo base_url().$image; ?>" style="width:100px;height:100px;" />&nbsp;
			<?php } ?>
		</td>
	</tr>
</table>