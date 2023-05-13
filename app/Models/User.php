<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'title',
        'fname',
        'lname',
        'email',
        'contact',
        'password',
        'email_verified_at',
        'approve_at',
        'status',
        'user_type'
    ];

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

    public function staff_profile()
    {
        return $this->hasOne(StaffProfile::class, 'user_id')->withDefault();
    }
    public function statehead_prfile()
    {
        return $this->hasOne(StateheadProfile::class, 'statehead_id')->withDefault();
    }
    public function agent_prfile()
    {
        return $this->hasOne(AgentProfile::class, 'agent_id')->withDefault();
    }
    public function retailer_prfile()
    {
        return $this->hasOne(RetailerProfile::class, 'retailer_id')->withDefault();
    }
    public function deliveryboy_profile()
    {
        return $this->hasOne(DeliveryboyProfile::class, 'deliveryboy_id')->withDefault();
    }
    public function customer_profile()
    {
        return $this->hasOne(CustomerProfile::class, 'customer_id')->withDefault();
    }
    public function customer_shipping()
    {
        return $this->hasOne(CustomerShippingaddress::class, 'customer_id')->withDefault();
    }
    public function customer_billing()
    {
        return $this->hasOne(CustomerBillingaddress::class, 'customer_id')->withDefault();
    }
    public function agent_bank()
    {
        return $this->hasOne(AgentBank::class, 'agent_id');
    }
}
