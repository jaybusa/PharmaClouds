const ENV = require('./env.js');
const mysql = require('mysql');

let mysql_config = {};

// switch(ENV){
// 	//Create all the variable explicitly under case block
// 	case "production":
// 	    mysql_config = {
// 		    host: '127.0.0.1',
// 		    user: 'root',
// 		    database: 'hairdresser',
// 		    charset : 'utf8mb4_general_ci'
// 	    }
//     break;
//     case "local":
// 	    mysql_config = {
// 		    host: '127.0.0.1',
// 		    user: 'root',
// 		    database: 'hairdresser',
// 		    charset : 'utf8mb4_general_ci'
// 	    }
//     break;
    
// }

var get_SQL_Connection = function () {
 
    switch(ENV){
        case "local":   
            mysqlConnection = mysql.createConnection({
                host: 'localhost',
                user: 'root',
                password: '',
                database: 'hairdresser',
                charset : 'utf8mb4',
                multipleStatements: true,
                port: 3306,
            });
        break;

        case "dev":
            mysqlConnection = mysql.createConnection({
                host: 'localhost',
                user: 'hoabexne_hairdresser_user',
                password: 'g?NkLfkph78b',
                database: 'hoabexne_hairdresser',
                charset : 'utf8mb4',
                multipleStatements: true,
                port: 3306,
            });
        break;  

        case "production":
            mysqlConnection = mysql.createConnection({
                host: 'localhost',
                user: 'quaferec_user',
                password: 'Wd6OAoyKveNc',
                database: 'quaferec_prod',
                charset : 'utf8mb4',
                multipleStatements: true,
                port: 3306,
            });
        break; 
    }

    

  // When connected
  mysqlConnection.connect (function (err) {
    if (err) {
      console.log ("SQL CONNECT ERROR&gt;&gt;" + err);
      setTimeout (get_SQL_Connection, 2000);// Retry when connection fails
    } else {
      console.log ("SQL CONNECT SUCCESSFUL.");
    }
  });
  // In case of error
  mysqlConnection.on ('error', function (err) {
    console.log ("SQL CONNECTION ERROR&gt;&gt;" + err);
    if (err.code === 'PROTOCOL_CONNECTION_LOST') {
      console.log ('=&gt;RECONECT ...');
      // reconnect
      get_SQL_Connection ();
    } else {
      throw err;
    }
  });
}
  
get_SQL_Connection ();


module.exports = {
	mysqlConnection,
}
