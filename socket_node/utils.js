//const {email_error} = require('./config');

var nodemailer = require('nodemailer');
/*var transporter = nodemailer.createTransport({
 service: 'gmail',
 auth: {
        user: email_error.user,
        pass: email_error.pass
    }
});*/

class Utils{
    constructor(){}
    send_error(error){
        /*const mailOptions = {
            to: email_error.to,
            bcc: email_error.bcc,
            from: email_error.from,
            fromname: 'FoodSafe',
            subject: 'FoodSafe Exception Occured',
            html: `<b>Host:</b>https://dev.3rddigital.com<br><br>
            <b>Message:</b> ${error.message} <br><br> 
            <b>Stack:</b> <pre>${error.stack}</pre>`
        }
        transporter.sendMail(mailOptions, function (err, info) {
           if(err)
             console.log(err)
           else
             console.log(info);
        });*/
    }
}

module.exports = Utils;