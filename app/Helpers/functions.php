<?php
use Carbon\Carbon;

define('ORDER_REQUEST', 1);
define('ORDER_TIMEOUT', 2);
define('ORDER_CANCEL', 3);
define('ORDER_ACCEPT', 4);
define('ORDER_ON_THE_WAY', 5);
define('ORDER_PROCESSING', 6);
define('ORDER_COMPLETE', 7);
define('ORDER_REJECT', 8);
define('ORDER_PAYMENT', 9);
define('ORDER_PAID', 10);
define('USER_OFFLINE', 11);
define('PAYMENT_TIMEOUT', 12);


define('USER_ACTIVE', 51);
define('USER_DEACTIVE', 52);

function pre($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit;
}

function veriftyAPITokenData() {
//	$header = Request::header('AuthorizationUser');
//    echo $header;exit;
    try {

        if (!empty(Request::header('Authorization'))) {
            $user = \JWTAuth::parseToken()->authenticate();
        } else {
            $user_id = null;
        }
        //$password = Hash::make("Ha#ir@dre%ss&er");
        //echo $password;exit;
        //echo Crypt::encrypt("apiuser:Ha#ir@dre%ss&er");exit;
        //echo $header;exit;
//        $user = \JWTAuth::authenticate($header);

//        $authorization_cred = \Illuminate\Support\Facades\Crypt::decrypt($header);
//        echo $authorization_cred;exit;
//        $expcred = explode(':', $authorization_cred);
//        $apiuser = $expcred[0];
//        $apipassword = $expcred[1];
//    } catch (\Exception $e) {
//        //echo 'heee:'.$e->getMessage();exit;
//        $message = Lang::get('messages.common.invalid_auth_token');
//        return InvalidResponse($message, 101);
//    }
        //echo $apiuser.':'.$apipassword;exit;
//    $user = \App\Models\User::where('email', $apiuser)->first();
        //pre($user);exit;
//    if ($user && Hash::check($apipassword, $user->password)) {


        if (!empty($user)) {
            \Log::info('Token success', ['token' => Request::header('Authorization')]);
            $message = Lang::get('messages.common.valid_auth_token');
            return SuccessResponse($message, 200, []);
        } else {
            $message = Lang::get('messages.common.invalid_auth_token');
            return InvalidResponse($message, 101);
        }
    }
    catch (Exception $exception){
        \Log::info('Token exception', ['token' => Request::header('Authorization'),'exception'=>$exception->getMessage()]);
        $message = Lang::get('messages.common.invalid_auth_token');
        return InvalidResponse($exception->getMessage(), 101);
    }
}

function InvalidResponse($message, $status_code) {
    return response()->json([
                'success' => false,
                'status_code' => $status_code,
                'message' => $message,
                'data' => array(),
    ]);
}

function SuccessResponse($message, $status_code, $data) {
    return response()->json([
                'success' => true,
                'status_code' => $status_code,
                'message' => $message,
                'data' => $data,
    ]);
}

function sendMail($path, $data, $email, $subject) {
        try {
            $mailSent = \Mail::send($path, $data, function($mail) use ($email, $subject) {
                        $mail->to($email);
                        $mail->subject($subject);
                    });
            //echo 'send';exit;
        } catch (\Exception $e) {
            //echo $e->getMessage();exit;
            Log::info('failed to send email = ' . json_encode($email) . " == " . $e->getMessage());
        }
    }

function send_otp_sms($mobile,$otp) {

    // DC SMS code
// 	$apiKey = 'fuXFqBsSecrn/Suc3MNxXRPSKp+LeQm5octzhX+FrKY=';
	$apiKey = '007dcb690c68706ff8590ad5cb0dce2c';
	$sender = 'Quafere';
	$msg = 'Your OTP for Pharmaclouds is '.$otp;
	$url = "https://api.taqnyat.sa/v1/messages";
	$url = str_replace(" ", '%20', $url);
	$url = str_replace("&amp;", '&', $url);
	$mobile = ltrim($mobile, "966");
    $mobile = ltrim($mobile, "+966");
    $mobile = ltrim($mobile, "00966");
    $mobile = ltrim($mobile, "0");
    $recipients = array("966".$mobile);
	$arrayToSend = json_encode(array('recipients' => $recipients, 'body' => $msg,'sender'=>$sender,'bearerTokens'=>$apiKey));
    // $headers[] = 'Authorization: X-API-KEY='. $apiKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayToSend);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $result = curl_exec($ch);
    curl_close($ch);
    // DC SMS code ends

    Logger()->info('send sms New',['mobile'=>$mobile,'arrayToSend'=>$arrayToSend,'otp'=>$otp,'response'=>$result]);

    //echo '<pre>';
    //print_r($result);
}

function send_otp_sms_old($mobile,$otp) {

    // DC SMS code
// 	$apiKey = 'fuXFqBsSecrn/Suc3MNxXRPSKp+LeQm5octzhX+FrKY=';
	$apiKey = 'jzN/htxq56Gl3i/AXWoP6Wqq+fe9Pfcm7y24GpGRi2g=';
	$sender = 'dc.net.sa';
	$msg = 'Your OTP for Pharmaclouds is '.$otp;
	$url = "https://sms.di.com.sa/api/send-sms";
	$url = str_replace(" ", '%20', $url);
	$url = str_replace("&amp;", '&', $url);
	$mobile = ltrim($mobile, "966");
    $mobile = ltrim($mobile, "+966");
    $mobile = ltrim($mobile, "00966");
    $mobile = ltrim($mobile, "0");
	$arrayToSend = array('mobile' => $mobile, 'message' => $msg,'country_code'=>'+966','X-API-KEY'=>$apiKey);
    $headers[] = 'Authorization: X-API-KEY='. $apiKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayToSend);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $result = curl_exec($ch);
    curl_close($ch);
    // DC SMS code ends

    Logger()->info('send sms',['mobile'=>$mobile,'arrayToSend'=>$arrayToSend,'otp'=>$otp,'response'=>$result]);

    //echo '<pre>';
    //print_r($result);
}

function commonPushNotification($user_id, $message_data, $other_data) {
    $device = \App\Models\UserDeviceToken::where('user_id', $user_id)->whereNull('deleted_at')->get();
    if (count($device) > 0) {
        foreach ($device as $d) {
            if ($d->token) {
                if($d->device_type == "IOS" && isset($message_data['sound'])){
                    $message_data['sound'] = 'notif_sound.mp3';
                    unset($message_data['android_channel_id']);
                }else if($d->device_type == "ANDROID" && isset($message_data['sound'])){
                    $message_data['sound'] = 'notif_sound';
                    $message_data['android_channel_id'] = 'sound_channel';
                }
                $device_tokens = array($d->token);
                $push_obj = new \App\PushNotifications();
                \Log::info('Message Data : ', ['message_data' => json_encode($message_data),'other_data' => json_encode($other_data)]);

                $t = $push_obj->androidReact($message_data, $device_tokens, $other_data);
                \Log::info('Push Send Status', ['Date' => date('Y-m-d H:i:s'), 'response' => $t, 'user_id' => $user_id]);
            }
        }
    }
}

function send_socket($data,$url) {
    $query = http_build_query($data);

    //echo $query;
    $env_socket_url = env('SOCKET_IP_URL');
    //echo $env_socket_url;
    $urlWebSocket = $env_socket_url . '/'.$url.'?' . $query;
//    echo $urlWebSocket; exit();
    $ch = curl_init();
    try {

        curl_setopt($ch, CURLOPT_URL, $urlWebSocket);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($ch);
        if($response===FALSE) {
            Logger()->info("Socket Curl failed: " . curl_error($ch));
            $html = "Socket Curl failed: " . curl_error($ch).': '.$urlWebSocket;

            $url = env('SITE_URL')."=".$url;

            $email = ['jitu.vank@gmail.com','app@di.net.sa'];
            //$eamil = ['anil.m@3rddigital.com'];
            try {
                \Mail::to($email)->send(new \App\Mail\ExceptionOccured($html, $url));
                Logger()->info("Socket failed mail send done");
            } catch(\Exception $e) {
                Logger()->info("Socket failed mail send exception =".$e->getMessage());
                //echo $e->getMessage();
            }

        }
        //echo 'Done:'.$response;
        curl_close($ch);

        Logger()->info('socket send success',['response'=>$response]);

        return $response;
    } catch (Exception $e) {
        Logger()->error('socket send error',['message'=>$e->getMessage()]);
        return 'Error:' . $e->getMessage();
    }
}

?>
