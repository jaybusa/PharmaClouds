<!DOCTYPE html>
<html>
<head>
	<title>Send Socket</title>
		<script src="{{asset('js/jquery-1.11.1.min.js')}}"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#send_socket").submit(function(){
					form_data = $("#send_socket").serialize();
					url = "{{route('post_send_socket')}}";
					$.post(url,form_data,function(response){
						$("#socket_send_message").html("Message send in socket. Please check");
						$("#message").val('');
					});
					return false;
				});
			});
		</script>
</head>
<body>
<h1>Send Socket</h1>
<form id="send_socket">
	@csrf
	<div id="socket_send_message" style="color: green"></div>
	<input type="text" name="message" required="" id="message">
	<button type="submit">Send</button>
</form>
</body>
</html>