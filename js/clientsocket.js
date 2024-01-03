var socketio = "";

function ioObject() {
        this.connectio = function () {
        switch (env) {
            case "production":
                socketio = io.connect("https://example.com/", {
                    transports: ['websocket'],
                    path: "/examplende/socket.io/"
                })
                break;
            case "local":
                socketio = io.connect('http://localhost:3005')
                break;

            case "dev":
                socketio = io.connect('http://hd.hoabex.net:3005')
                break;
            
        }        
        };
}
;
function getObject() {
        this.getobj = function () {
               return socketio;
        };
}
;
