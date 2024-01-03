<!DOCTYPE html>
<html dir="<?php echo $config['dir']; ?>" lang="<?php echo $config['lang']; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?> | <?php echo lang('app_name'); ?></title>
	
	<!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
	<style>
		body { background:#000;}
		.logo-container {margin:0 auto;max-width:300px;margin-top:10%;}
	</style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="logo-container">
	<img src="<?php echo base_url(); ?>assets/img/logo.png" class="img-responsive center-block" /><br />
	<div class="text-center"><a href="<?php echo base_url(); ?>login" class="btn btn-default"><?php echo lang('login'); ?></a></div>
</div>
</body>
</html>