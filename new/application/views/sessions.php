        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
				<div class="row page-links">
					<div class="col-sm-6 text-left">
						<ul class="breadcrumb">
							<li><?php echo lang('sessions'); ?></li>
						</ul>
					</div>
					<div class="col-sm-6 text-right">
						<?php if(isset($username)) { ?>
						<a href="<?php echo base_url(); ?>sessions" class="btn btn-default btn-sm"><i class="fa fa-list"></i> <?php echo lang('list_sessions'); ?></a>
						<?php } ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1><?php echo lang('sessions'); ?></h1>
                    </div>
				</div>
				<div class="row data">
					<div class="col-sm-12">
						<div class="information">
							<ul>
								<?php if(isset($username)) echo "<li>Sessions for User &quot;".$username."&quot;</li>"; ?>
							</ul>
						</div>
						<table class="table table-striped">
							<tr>
								<th class="timestamp text-center"><?php echo lang('time_gmt'); ?></th>
								<th class="text-left"><?php echo lang('user_ip'); ?></th>
								<th class="text-center"><?php echo lang('username'); ?></th>
								<th class="operations"></th>
							</tr>
							<?php if(!empty($sessions)) foreach($sessions as $session) { ?>
							<tr>
								<td class="timestamp-time text-center"><?php echo date('j M, o g:i A',strtotime($session->login_time)); ?></td>
								<td class="text-left"><?php echo $session->user_ip; ?></td>
								<td class="text-center"><a href="<?php echo base_url(); ?>users/view/<?php echo $session->username; ?>"><?php echo $session->username; ?></a></td>
								<td><a href="<?php echo base_url(); ?>sessions/delete/<?php echo url_id_encode($session->id); ?>" class="btn btn-danger btn-xs" onclick="Javascript: return confirm('<?php echo lang('really_want_to_delete'); ?>');"><i class="fa fa-trash"></i></a></td>
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