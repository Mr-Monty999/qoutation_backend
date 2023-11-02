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

    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class, "user_service_type", "user_id");
    }

    public function services()
    {
        return $this->hasMany(Service::class, "user_id");
    }

    public function otps()
    {
        return $this->hasMany(UserOtp::class, "user_id");
    }

    public function serviceQoutations()
    {
        return $this->hasMany(ServiceQoutation::class, "user_id");
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, "user_id");
    }


    public function walletTransactions()
    {

        return $this->hasMany(WalletTransaction::class, "user_id");
    }
}
