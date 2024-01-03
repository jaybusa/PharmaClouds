<?php

namespace App;

// Server file
class PushNotifications {

    // (Android/ios)API access key.
    private static $API_ACCESS_KEY = 'AAAAJnU3qSI:APA91bHXz5kpE5Eas5ZHkYVyzEad1pkjBEFXPxbEJCuhabAvHrxkD08wZEfiZH6T_L2nUPsqx92LkAaSbD4-A04psF465SoNTTBiNUWF0nUUNydhOHPVIrNC26iObWpQjrgYripNxt1M';

    private function useCurl($url, $headers, $fields) {
        // Open connection
        $ch = curl_init();
        if ($url) {
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if ($fields) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            }

            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                //die('Curl failed: ' . curl_error($ch));
                \Log::info('Push Send Curl failed', ['Date' => date('Y-m-d H:i:s'), 'curl_error' => curl_error($ch)]);

            }

            // Close connection
            curl_close($ch);

            return $result;
        }
    }

    public function androidReact($message, $reg_id, $data = array(), $type = 0) {
        $url = 'https://fcm.googleapis.com/fcm/send';

        // 'to' => $singleID ; // expecting a single ID
        // 'registration_ids' => $registrationIDs ; // expects an array of ids
        // 'priority' => 'high' ; // options are normal and high, if not set, defaults to high.
        $fcmFields = array(
            'registration_ids' => $reg_id,
            'priority' => 'high',
            'notification' => $message,
            'data' => $data
        );

        $headers = array(
            'Authorization: key=' . self::$API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        return $this->useCurl($url, $headers, json_encode($fcmFields));
    }

}

?>