        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links hidden-print">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><?php echo lang('report'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<a onclick="javascript:window.print();return false;" href="#" class="btn btn-default"><i class="fa fa-print"></i> <?php echo lang('print'); ?></a>
					</div>
				</div>
				<div class="row visible-print">
					<div class="col-sm-12">
						<?php
						switch($stage) {
							case "": $stage_name=lang('all');
							case 1: $stage_name=lang('new');
							case 2: $stage_name=lang('in_progress');
							case 3: $stage_name=lang('pending');
							case 4: $stage_name=lang('completed');
							default: $stage_name=lang('all');
						}
						switch($order_by) {
							case "id": $order_by_name=lang('id'); break;
							case "car_id": $order_by_name=lang('car_id'); break;
							case "check_in": $order_by_name=lang('check_in'); break;
							case "check_out": $order_by_name=lang('check_out'); break;
							case "stage": $order_by_name=lang('stage'); break;
							default: $order_by_name=lang('id');
						}
						?>
						<?php echo lang('stage'); ?>: <?php echo $stage_name; ?> &nbsp; | &nbsp; <?php echo lang('from_date'); ?>: <?php echo $first_date; ?> &nbsp; | &nbsp; <?php echo lang('to_date'); ?>: <?php echo $second_date; ?> &nbsp; | &nbsp; <?php echo lang('order_by'); ?>: <?php echo $order_by_name; ?> 
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo lang('report'); ?></h1>
                    </div>
				</div>
				<div class="row data">
					<div class="col-sm-12">
						<div class="information hidden-print">
							<form action="#" method="get" id="reportFilter">
								<div class="form-group col-sm-2">
									<?php echo lang('stage'); ?>:<br />
									<select name="stage" class="form-control">
										<option value="" <?php if($stage==0) echo "selected"; ?>><?php echo lang('all'); ?></option>
										<option value="1" <?php if($stage==1) echo "selected"; ?>><?php echo lang('new'); ?></option>
										<option value="2" <?php if($stage==2) echo "selected"; ?>><?php echo lang('in_progress'); ?></option>
										<option value="3" <?php if($stage==3) echo "selected"; ?>><?php echo lang('pending'); ?></option>
										<option value="4" <?php if($stage==4) echo "selected"; ?>><?php echo lang('completed'); ?></option>
									</select>
								</div>
								<div class="form-group col-sm-3">
									<?php echo lang('from_date'); ?>:<br />
									<input type="date" name="first_date" class="form-control" value="<?php if(!empty($first_date)) echo date('Y-m-d',strtotime($first_date)); ?>" />
								</div>
								<div class="form-group col-sm-3">
									<?php echo lang('to_date'); ?>:<br />
									<input type="date" name="second_date" class="form-control" value="<?php if(!empty($second_date)) echo date('Y-m-d',strtotime($second_date)); ?>" />
								</div>
								<div class="form-group col-sm-2">
									<?php echo lang('order_by'); ?>:<br />
									<select name="order_by" class="form-control">
										<option value="id" <?php if($order_by=='id') echo "selected"; ?>><?php echo lang('id'); ?></option>
										<option value="car_id" <?php if($order_by=='car_id') echo "selected"; ?>><?php echo lang('car_id'); ?></option>
										<option value="check_in" <?php if($order_by=='check_in') echo "selected"; ?>><?php echo lang('check_in_date'); ?></option>
										<option value="check_out" <?php if($order_by=='check_out') echo "selected"; ?>><?php echo lang('check_out_date'); ?></option>
										<option value="stage" <?php if($order_by=='stage') echo "selected"; ?>><?php echo lang('stage'); ?></option>
									</select>
								</div>
								<div class="col-sm-1">
									<br />
									<input type="submit" name="filter-btn" class="btn btn-default" value="<?php echo lang('filter'); ?>" />
								</div>
							</form>
						</div>
						<table class="table table-striped">
							<tr>
								<td colspan="5" class="text-left">
									<strong><?php echo count($report_results)." ".lang('results_found'); ?>.</strong>
								</td>
							</tr>
							<tr>
								<th class="text-left"><?php echo lang('car_id'); ?></th>
								<th class="text-center"><?php echo lang('check_in'); ?></th>
								<th class="text-center"><?php echo lang('check_out'); ?></th>
								<th class="text-center stage"><?php echo lang('stage'); ?></th>
								<th class="operations hidden-print"></th>
							</tr>
							<?php if(!empty($report_results)) foreach($report_results as $data) {
								if($data->stage==1) { $stage=lang('new'); $stage_class="red-state"; }
								if($data->stage==2) { $stage=lang('in_progress'); $stage_class="yellow-state"; }
								if($data->stage==3) { $stage=lang('pending'); $stage_class="green-state"; }
								if($data->stage==4) { $stage=lang('completed'); $stage_class="green-state"; }
								?>
							<tr>
								<td class="text-left"><a href="<?php echo base_url(); ?>check-ins/view/<?php echo url_id_encode($data->id); ?>"><?php echo $data->car_id; ?></a></td>
								<td class="text-center"><?php echo date('j M, o g:i A',strtotime($data->check_in)); ?></td>
								<td class="text-center"><?php if(!empty($data->check_out)) echo date('j M, o g:i A',strtotime($data->check_out)); else echo lang('not_available'); ?></td>
								<td class="text-center <?php echo $stage_class; ?>"><?php echo $stage; ?></td>
								<td class="hidden-print">
								<?php if(isset($permission_data['check_in_edit'])) { ?>
								<a href="<?php echo base_url(); ?>check-ins/edit/<?php echo url_id_encode($data->id); ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a> &nbsp;
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