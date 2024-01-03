<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function send_socket($data,$url) {
    $query = http_build_query($data);

    //echo $query;
    $env_socket_url = env('SOCKET_IP_URL');
    //echo $env_socket_url;
    $urlWebSocket = $env_socket_url . '/'.$url.'?' . $query;
    //echo $urlWebSocket;
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
            Logger()->info("Curl failed: " . curl_error($ch));
        }
        //echo 'Done:'.$response;
        curl_close($ch);

        Logger()->info($response);

        return $response;
    } catch (Exception $e) {
        Logger()->info('send socket response Error', $e->getMessage());
        return 'Error:' . $e->getMessage();
    }
}
}
