$("#menu-toggle").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled");
});
$(document).ready(function(){
	/* Setting dashboard size on load */
	var wHeight=$(window).height();
	$('.dashboard').height(wHeight);
	
	/* Check-in Form Validation */
	$(document).ready(function(){
		$("select[name=car_id]").chosen();
		// validation of chosen on change
		if ($("select[name=car_id]").length > 0) {
			$("select[name=car_id]").each(function() {
				if ($(this).attr('required') !== undefined) {
					$(this).on("change", function() {
						$(this).valid();
					});
				}
			});
		}
	});
	
	/* Customer Form Validation */
	$(document).ready(function(){
		$("select[name=type]").chosen();
		// validation of chosen on change
		if ($("select[name=type]").length > 0) {
			$("select[name=type]").each(function() {
				if ($(this).attr('required') !== undefined) {
					$(this).on("change", function() {
						$(this).valid();
					});
				}
			});
		}
	});
	
	/* Car form validation */
	$("select[name=owner]").chosen();
	// validation of chosen on change
	if ($("select[name=owner]").length > 0) {
		$("select[name=owner]").each(function() {
			if ($(this).attr('required') !== undefined) {
				$(this).on("change", function() {
					$(this).valid();
				});
			}
		});
	}
	$("select[name=model]").chosen();
	// validation of chosen on change
	if ($("select[name=model]").length > 0) {
		$("select[name=model]").each(function() {
			if ($(this).attr('required') !== undefined) {
				$(this).on("change", function() {
					$(this).valid();
				});
			}
		});
	}
	$("select[name=type]").chosen();
	// validation of chosen on change
	if ($("select[name=type]").length > 0) {
		$("select[name=type]").each(function() {
			if ($(this).attr('required') !== undefined) {
				$(this).on("change", function() {
					$(this).valid();
				});
			}
		});
	}
	
	
	/* validation of form fields General */
	$.validator.setDefaults({ ignore: ":hidden:not(select)" });
	$('form').validate({
		errorPlacement: function (error, element) {
			if (element.is("select.chosen")) {
				element.parent().find('.input-error').append(error);
			} else {
				error.insertAfter(element);
			}
		}
	});
});

$(window).resize(function(){
	/* Setting dashboard size on resize */
	var wHeight=$(window).height();
	$('.dashboard').height(wHeight);
});

function validateCustomerForm() {
	var errFlag=0;
	var reg= /^[a-zA-Z0-9]$/;
	var phone=$('input[name=phone]').val();
	if(phone.length!=12 || phone.substring(0,2)!=96) { 
		$("#phone_error").html('Phone number should start with "96" and has length of 12 characters.');
		errFlag=1;
	} else { $("#phone_error").empty(); }
	
	if(errFlag==1) return false; else return true;
}

function validateUserForm() {
		var errFlag=0;
		var phone=$('input[name=phone]').val();
		if(phone.length!=12 || phone.substring(0,2)!=96) { 
			$("#phone_error").html('Phone number should start with "96" and has length of 12 characters.');
			errFlag=1;
		} else { $("#phone_error").empty(); }
		
		if(errFlag==1) return false; else return true;
	}

function validateNewUserForm() {
	var errFlag=0;
	var reg= /^[a-zA-Z0-9]$/;
	var phone=$('input[name=phone]').val();
	if(phone.length!=12 || phone.substring(0,2)!=96) { 
		$("#phone_error").html('Phone number should start with "96" and has length of 12 characters.');
		errFlag=1;
	} else { $("#phone_error").empty(); }
	
	var pass=$('input[name=pass]').val();
	var conpass=$('input[name=conpass]').val();
	if(pass.length<8 || pass.length>15 || !checkPwd(pass)) {
		$("#pass_error").html('Password length 8-15. It should be alphanumeric.');
		errFlag=1;
	} else {
		$("#pass_error").empty();
	}
	if(pass!=conpass) {
		$("#conpass_error").html('Confirm Password not matching with Password.');
		errFlag=1;
	} else {
		$("#conpass_error").empty();
	}
	
	if(errFlag==1) return false; else return true;
}
function checkPwd(str) {
	if (str.length < 8) {
		return false;
	} else if (str.length > 50) {
		return false;
	} else if (str.search(/\d/) == -1) {
		return false;
	} else if (str.search(/[a-zA-Z]/) == -1) {
		return false;
	} else if (str.search(/[^a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\_\+]/) != -1) {
		return false;
	}
	return true;
}