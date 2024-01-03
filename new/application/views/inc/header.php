<!DOCTYPE html>
<html dir="<?php echo $config['dir']; ?>" lang="<?php echo $config['lang']; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?> | <?php echo lang('app_name'); ?></title>

	<link href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" rel="stylesheet" />
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
	
	<?php if($config['dir']=='rtl') { ?>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-rtl.min.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
	<?php } ?>
	<link href="<?php echo base_url(); ?>assets/chosen/chosen.min.css?ver=<?php echo $config['version']; ?>" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/chosen/chosen-bootstrap.css?ver=<?php echo $config['version']; ?>" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css?ver=<?php echo $config['version']; ?>" rel="stylesheet" />
	
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>assets/fa/css/font-awesome.min.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
	
	<?php if($config['dir']=='rtl') { ?>
    <link href="<?php echo base_url(); ?>assets/css/style-rtl.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
	<?php } else { ?>
	<link href="<?php echo base_url(); ?>assets/css/style-ltr.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
	<?php } ?>
	
	<?php if(isset($dataTable)) { ?>
	<link href="<?php echo base_url(); ?>assets/css/dataTables.bootstrap.min.css?ver=<?php echo $config['version']; ?>"rel="stylesheet" />
	<?php } ?>
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	
	<!-- jQuery -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.1.1.min.js?ver=<?php echo $config['version']; ?>"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js?ver=<?php echo $config['version']; ?>"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js?ver=<?php echo $config['version']; ?>"></script>
    <script src="<?php echo base_url(); ?>assets/chosen/chosen.jquery.min.js?ver=<?php echo $config['version']; ?>"></script>
	<script src="<?php echo base_url(); ?>assets/js/modernizr-custom.js?ver=<?php echo $config['version']; ?>"></script>
	<script src="<?php echo base_url(); ?>assets/js/moment.js?ver=<?php echo $config['version']; ?>"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js?ver=<?php echo $config['version']; ?>"></script>
	<?php if(isset($scanner)) { ?>
	<script>
	function isMobile() {
		return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
	}

	if (!isMobile()) {
		document.write('<script src="<?php echo base_url(); ?>assets/scannerjs/scanner.js?ver=<?php echo $config['version']; ?>" />');
	}
	</script>
	<?php } ?>
	<script>
	if (!Modernizr.inputtypes.date) {
		/* get jQuery-ui css */
		$('', {
		  rel: 'stylesheet',
		  type: 'text/css',
		  href: 'https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css'
		}).appendTo('head');
		/* get jQuery-ui */
		jQuery.getScript('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js')
		/* wait till it's loaded */
		.done(function() {
		  /* apply datepicker ui to selected element */
		  $('input[type=date]').datepicker({
		  /* Keep the date consistent */
		  dateFormat: 'yy-mm-dd'
		});
	  });
	}
	</script>
	
	<?php if(isset($dataTable)) { ?>
	<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js?ver=<?php echo $config['version']; ?>"></script>
	<script src="<?php echo base_url(); ?>assets/js/dataTables.bootstrap.min.js?ver=<?php echo $config['version']; ?>"></script>
	<?php } ?>

</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header pull-left">
            <a href="#menu-toggle" class="btn btn-bars pull-left" id="menu-toggle"><i class="fa fa-bars"></i></a>
			<a class="navbar-brand" href="<?php echo base_url(); ?>dashboard"><?php echo lang('app_name'); ?></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-right" id="top-navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php /*
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span
                    class="glyphicon glyphicon-comment"></span><span class="hidden-xs">Chats</span> <span class="label label-primary">42</span>
                </a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><span class="label label-warning">7:00 AM</span>Hi :)</a></li>
                        <li><a href="#"><span class="label label-warning">8:00 AM</span>How are you?</a></li>
                        <li><a href="#"><span class="label label-warning">9:00 AM</span>What are you doing?</a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="text-center">View All</a></li>
                    </ul>
                </li>
                <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="glyphicon glyphicon-envelope"></span><span class="hidden-xs">Inbox</span> <span class="label label-info">32</span>
					</a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><span class="label label-warning">4:00 AM</span>Favourites Snippet</a></li>
                        <li><a href="#"><span class="label label-warning">4:30 AM</span>Email marketing</a></li>
                        <li><a href="#"><span class="label label-warning">5:00 AM</span>Subscriber focused email design</a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="text-center">View All</a></li>
                    </ul>
                </li>
				*/ ?>
                <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="glyphicon glyphicon-user"></span><span class="hidden-xs"><?php echo $user_data->name; ?></span> <b class="caret"></b>
					</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url(); ?>settings"><span class="glyphicon glyphicon-cog"></span><?php echo lang('settings'); ?></a></li>
                        <li><a href="<?php echo base_url(); ?>change-language/<?php if(isset($_SESSION['lang']) && $_SESSION['lang']=='ar') echo "en"; else echo "ar"; ?>?uri=<?php echo $_SERVER['REQUEST_URI'];?>"><span class="glyphicon glyphicon-globe"></span><?php if(isset($_SESSION['lang']) && $_SESSION['lang']=='ar') echo lang('english'); else echo lang('arabic'); ?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url(); ?>logout"><span class="glyphicon glyphicon-off"></span><?php echo lang('logout'); ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
			<div class="logo"><a href="<?php echo base_url(); ?>dashboard"><img src="<?php echo base_url(); ?>assets/img/logo.png" class="img-responsive center-block" /></a></div>
			<div class="menu-list">
				<ul id="menu-content" class="menu-content">
					<?php if(isset($permission_data['users'])) { ?>
					<li <?php if($page=="new-user" || $page=="edit-user" || $page=="view-user" || $page=="users") { ?>class="active"<?php } ?>>
						<a href="<?php echo base_url(); ?>users">
							<i class="fa fa-users fa-lg"></i> <?php echo lang('users'); ?>
						</a>
					</li>
					<?php } ?>
					<?php if(isset($permission_data['customers'])) { ?>
					<li <?php if($page=="new-customer" || $page=="edit-customer" || $page=="view-customer" || $page=="customers") { ?>class="active"<?php } ?>>
						<a href="<?php echo base_url(); ?>customers">
							<i class="fa fa-vcard fa-lg"></i> <?php echo lang('customers'); ?>
						</a>
					</li>
					<?php } ?>
					<?php if(isset($permission_data['customer_types'])) { ?>
					<li <?php if($page=="new-customer-type" || $page=="edit-customer-type" || $page=="view-customer-type" || $page=="customer-types") { ?>class="active"<?php } ?>>
						<a href="<?php echo base_url(); ?>customer-types">
							<i class="fa fa-users fa-lg"></i> <?php echo lang('customer_types'); ?>
						</a>
					</li>
					<?php } ?>
					<?php if(isset($permission_data['cars'])) { ?>
					<li <?php if($page=="new-car" || $page=="edit-car" || $page=="view-car" || $page=="cars") { ?>class="active"<?php } ?>>
						<a href="<?php echo base_url(); ?>cars">
							<i class="fa fa-car fa-lg"></i> <?php echo lang('cars'); ?>
						</a>
					</li>
					<?php } ?>
					<?php if(isset($permission_data['car_types'])) { ?>
					<li <?php if($page=="new-car-type" || $page=="edit-car-type" || $page=="car-types") { ?>class="active"<?php } ?>>
						<a href="<?php echo base_url(); ?>car-types">
							<i class="fa fa-users fa-lg"></i> <?php echo lang('car_types'); ?>
						</a>
					</li>
					<?php } ?>
					<?php if(isset($permission_data['check_ins'])) { ?>
					<li <?php if($page=="new-check-in" || $page=="edit-check-in" || $page=="view-check-in" || $page=="check-ins") { ?>class="active"<?php } ?>>
						<a href="<?php echo base_url(); ?>check-ins">
							<i class="fa fa-exchange fa-lg"></i> <?php echo lang('check_in_out'); ?>
						</a>
					</li>
					<?php } ?>
					<?php if(isset($permission_data['reports'])) { ?>
					<li <?php if($page=="reports") { ?>class="active"<?php } ?>>
						<a href="<?php echo base_url(); ?>reports">
							<i class="fa fa-bars fa-lg"></i> <?php echo lang('report'); ?>
						</a>
					</li>
					<?php } ?>
					<?php /*if(isset($permission_data['invoices'])) { ?>
					<li <?php if($page=="new-invoice" || $page=="edit-invoice" || $page=="view-invoice" || $page=="invoices") { ?>class="active"<?php } ?>>
						<a href="<?php echo base_url(); ?>invoices">
							<i class="fa fa-file-o fa-lg"></i> <?php echo lang('invoices'); ?>
						</a>
					</li>
					<?php }*/ ?>
					<?php /*if(isset($permission_data['spareparts'])) { ?>
					<li <?php if($page=="new-spare-part" || $page=="edit-spare-part" || $page=="view-spare-part" || $page=="spare-parts") { ?>class="active"<?php } ?>>
						<a href="<?php echo base_url(); ?>spare-parts">
							<i class="fa fa-cogs fa-lg"></i> <?php echo lang('spare_parts'); ?>
						</a>
					</li>
					<?php }*/ ?>
					<?php if(isset($permission_data['sessions'])) { ?>
					<li <?php if($page=="sessions") { ?>class="active"<?php } ?>>
						<a href="<?php echo base_url(); ?>sessions">
							<i class="fa fa-globe fa-lg"></i> <?php echo lang('sessions'); ?>
						</a>
					</li>
					<?php } ?>
				</ul> <!-- /.menu-content -->
			</div><!-- /.menu-list -->
			
        </div>
        <!-- /#sidebar-wrapper -->