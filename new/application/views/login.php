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
		<h1><?php echo lang('admin_login'); ?></h1><br>
		<form method="post" action="<?php echo base_url(); ?>login" name="login-form" id="login-form">
			<input type="hidden" name="login_form_submit" value="true" />
			<div id="enterOtp" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content form">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><?php echo lang('enter_otp'); ?></h4>
						</div>
						<div class="modal-body row">
							<div class="otp_error"></div>
							<div class="col-sm-12 form-group">
								<input type="number" name="otp_value" class="form-control" placeholder="<?php echo lang('enter_otp'); ?>" />
								<div id="sample_otp"></div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="submit" name="login-btn" class="login loginmodal-submit" value="<?php echo lang('login'); ?>" onclick="return validateOTP();" />
							<div class="row text-center">
								<div id="otp-success"></div>
								<div id="otp-failed"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="text" name="user" placeholder="<?php echo lang('username'); ?>" required />
			<input type="password" name="pass" placeholder="<?php echo lang('password'); ?>" required />
			
			<?php if($otpEnabled) { ?>
				<a class="login loginmodal-submit" href="#" onclick="Javascript:return sendOTP();"><?php echo lang('login'); ?></a>
			<?php } else { ?>
				<input type="submit" name="login-btn" class="login loginmodal-submit" value="<?php echo lang('login'); ?>" />
			<?php } ?>
			<label style="font-weight:400;"><input type="checkbox" name="remember_me" /> <?php echo lang('remember_me'); ?></label>
		</form>
		<div class="login-help">
			<a href="<?php echo base_url(); ?>forgot-password"><?php echo lang('forgot_password'); ?></a>
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
					$("#otp-success").html('<?php echo lang("otp_successfully_sent"); ?>');
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
	var otpLeft=3;
	function validateOTP(){
		var otpValue=$('input[name=otp_value]').val();
		var error=0;
		if(otpLeft==1) {
			return true;
		}
		$.post( "<?php echo base_url(); ?>validate-otp",{ otp: otpValue }, function( data ) {
			if(data== -1) {
				otpLeft--;error=1;
				$(".otp_error").html('<div class="alert alert-danger"><?php echo lang('otp_attempt_left'); ?> '+otpLeft+'</div>');
				return false;
			} else { $('#login-form').submit(); }
		});
		return false;
	}
	</script>
	<?php } ?>
</body>
</html>