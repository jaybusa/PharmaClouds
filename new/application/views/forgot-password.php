<!DOCTYPE html>
<html dir="<?php echo $config['dir']; ?>" lang="<?php echo $config['lang']; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1, maximum-scale=1, user-scalable=0">
    
    <title><?php echo $title; ?> | <?php echo lang('app_name'); ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
	
	<?php if($config['dir']=='rtl') { ?>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-rtl.min.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
	<?php } ?>
	
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>assets/fa/css/font-awesome.min.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/login.css?ver=<?php echo $config['version']; ?>" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

	<div class="login-container">
		<div class="logo">
			<a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/img/logo.png" class="img-responsive center-block" /></a>
		</div>
		<div class="errors">
			<?php if(validation_errors()) { ?><div class="alert alert-danger"><?php echo validation_errors(); ?></div><?php } ?>
			<?php if(isset($alertMsg)) { echo $alertMsg; } ?>
		</div>
		<h1><?php echo lang('forgot_password'); ?></h1><br>
		<form method="post" action="<?php echo base_url(); ?>forgot-password" name="forgot-form" id="forgot-form">
			<input type="email" name="email" placeholder="<?php echo lang('email'); ?>" value="<?php echo set_value('email'); ?>" required />			
			<input type="submit" name="forgot-btn" class="login loginmodal-submit" value="<?php echo lang('send_new_pass'); ?>" />
		</form>
		<div class="login-help">
			<a href="<?php echo base_url(); ?>login"><?php echo lang('login'); ?></a>
		</div>
	</div>
	<!-- jQuery -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.1.1.min.js?ver=<?php echo $config['version']; ?>"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js?ver=<?php echo $config['version']; ?>"></script>
    <!-- Menu Toggle Script -->
	<?php if($otpEnabled) { ?>
	<script>
	function sendOTP() {
		var user = $('#login-form').find( "input[name='user']" ).val();
		var pass = $('#login-form').find( "input[name='pass']" ).val();
		$.post( "<?php echo base_url(); ?>send-otp",{ user: user, pass: pass }, function( data ) {
			if(data=="-1") $(".errors").html('<div class="alert alert-danger"><?php echo lang("incorrect_user_pass"); ?></div>');
			else {
				if(data=="1") {
					$("#otp-success").html('<?php echo lang("otp_successfully_sent").$_SESSION['otp_value']; ?>');
				} else {
					$("#otp-failed").html('<?php echo lang("otp_not_sent"); ?>');
				}
				$("#enterOtp").modal('show');
			}
		});
	}
	$('#login-form').on('keyup keypress', function(e) {
	  var keyCode = e.keyCode || e.which;
	  if (keyCode === 13) {
		e.preventDefault();
		return false;
	  }
	});
	</script>
	<?php } ?>
</body>
</html>