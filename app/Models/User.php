<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExceptionOccured;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [
        'name', 'email', 'password',
    ];*/
    protected $guarded = [];
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

    protected $dates = ['deleted_at'];
    
    

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    

    public static function sendMail($path, $data, $email, $subject) {
        try {
            $mailSent = Mail::send($path, $data, function($mail) use ($email, $subject) {
                        $mail->to($email);
                        $mail->subject($subject);
                    });
        } catch (\Exception $e) {
//            echo $e->getMessage();
            Log::info('failed to send email = ' . json_encode($email) . " == " . $e->getMessage());
        }
    }

    public function service() {
        return $this->hasMany('\App\Models\PharmacloudsService','hairdresser_id','id');
    }

    public function orders() {
        return $this->hasMany('\App\Models\Order','customer_id','id');
    }

    public function hairdressor_order() {
        return $this->hasMany('\App\Models\Order','hairdressor_id','id');
    }

    public static function updateWallet($user_id) {
        $wallet_total = \App\Models\UserWalletHistory::where('user_id',$user_id)->sum('amount');
        Logger()->info("updateWallet user_id=" . $user_id);
        Logger()->info("updateWallet " . $wallet_total);
        \App\Models\User::where('id',$user_id)->update(['wallet_total'=>$wallet_total]);
    }

    public function favorite() {
        return $this->hasOne('\App\Models\UserFavorite','hairdressor_id','id');
    }
    public function review_list() {
        return $this->hasMany('\App\Models\OrderReview','receiver_id','id');
    }

    public function avgRating() {
        return $this->hasMany('\App\Models\OrderReview','receiver_id','id')->selectRaw('avg(rating) as avg_rating,receiver_id');
    }

    public function reviews() {
        return $this->hasMany('\App\Models\OrderReview','receiver_id','id');
    }
    

    public function totalEarning() {
        return $this->hasMany('\App\Models\Order','hairdresser_id','id')->where('order_status',7)->selectRaw('sum(hairdressor_amount) as total_earning,hairdresser_id');
    }

    
}
