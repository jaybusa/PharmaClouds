<html>
<head>
	<title>receive socket</title>
	<script src="{{asset('js/jquery-1.11.1.min.js')}}"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
    <script src="{{ URL::asset('js/env.js') }}"></script>
    <script src="{{ URL::asset('js/clientsocket.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/toastr.min.js')}}"></script>



    <?php 
    $join_room = 1;
    ?>
    <script type="text/javascript">

    	$(window).on( 'load', function() {
    		var myNewObject = new ioObject();
		    var getobj = new getObject();

		    myNewObject.connectio();
		    var socket = getobj.getobj();
		    socket.on('connect', function () {
		        var join_room =<?php echo $join_room; ?>;
		        var socket = getobj.getobj();
		        if(join_room!='') {
		                    //alert("connected");

		            socket.emit('room', join_room);
		            console.log('connect')
		        }
		    });

		    socket.on('connect_timeout', (timeout) => {
		       var join_room=<?php echo $join_room; ?>;
		        var socket = getobj.getobj();
		        socket.emit('room', join_room);
		        console.log('timeout')
		        //$('.online_icon').addClass('offline')
		        //socketErrorLog(timeout + "__connect_timeout__" + join_room)
		    });
		    socket.on('disconnect', function(reason) {   
		         var join_room=<?php echo $join_room; ?>;                 
		         console.log('disconnect:'+reason)               
		         //$('.online_icon').addClass('offline')
		         //socketErrorLog(reason + "__disconnect__" + join_room)
		         if (reason === 'io server disconnect') {                    
		            socket.connect();
		         }
		    });
		    socket.on("reconnect", function() {                
		        console.log('reconnect') 
		        var join_room=<?php echo $join_room; ?>;
		        var socket = getobj.getobj();
		        socket.emit('room', join_room);
		    });

		    socket.on("connect_error", function(error) {               
		      console.log("connect_error:"+error);
		      
		    });
		    socket.on("reconnect_error", function(error) {               
		        console.log("reconnect_error:"+error);
		     
		    });
		    socket.on('error', function(error) {
		     console.log("error socket:"+error);
		     
		    });

		    socket.on('send_notification', function (response) {
		    	console.log('send_notification receive');
		    	//alert(response.message);
		    	var result = response.result;
        		var message = result.message;
        		received_socket_data = $("#received_socket_data").html();
        		if(received_socket_data=='') {
        			$("#received_socket_data").html(message);
        		} else {
        			$("#received_socket_data").append("<br>"+message);
        		}
		    });
    	});

    
    </script>
</head>
<body>
<div>Waiting for socket message</div>
<div id="received_socket_data" style="color:green"></div>
</body>
</html>