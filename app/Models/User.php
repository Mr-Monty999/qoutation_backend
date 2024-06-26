<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = ["id"];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class, "user_id")
        ->where("expired_at", ">", now())
        ->latest();
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, "user_id");
    }

    public function buyer()
    {
        return $this->hasOne(Buyer::class, "user_id");
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, "user_id");
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, "user_id");
    }

    public function services()
    {
        return $this->hasMany(Service::class, "user_id");
    }

    // public function otps()
    // {
    //     return $this->hasMany(UserOtp::class, "user_id");
    // }


    public function wallet()
    {
        return $this->hasOne(Wallet::class, "user_id");
    }


    public function walletTransactions()
    {

        return $this->hasMany(WalletTransaction::class, "user_id");
    }
    public function activities()
    {
        return $this->belongsToMany(Activity::class, "user_activity", "user_id");
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, "sender_id");
    }
    public function recipientMessages()
    {
        return $this->hasMany(MessageRecipient::class, "receiver_id");
    }

    public function country()
    {
        return $this->belongsTo(Country::class, "country_id");
    }
    public function city()
    {
        return $this->belongsTo(City::class, "city_id");
    }
    public function neighbourhood()
    {
        return $this->belongsTo(Neighbourhood::class, "neighbourhood_id");
    }
    public function phone()
    {
        return $this->hasOne(UserPhone::class, "user_id");
    }
}
