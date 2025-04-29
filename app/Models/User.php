<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
     use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'google_id',
        'facebook_id',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'rule',
        'birthdate',
        'gender',
        'path_photo',
        'biography',
        'province',
        'country',
        'municipality',
        'postal_code',
        'deleted',
    ];
    /**
     * Accessor to get the full URL for the path_photo attribute.
     *
     * @return string|null
     */
    public function getPathPhotoAttribute(?string $value): ?string
    {
        return $value ? asset('storage/' . $value) : null;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Document>
     */
    public function document(){

        return $this->hasMany(Document::class);
        
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Agency>
     */
    public function agencies(){

        return $this->hasMany(Agency::class);
        
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Business>
     */
    public function business(){

        return $this->hasMany(Business::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<CheckPoint>
     */
    public function checkPoint(){

        return $this->hasMany(CheckPoint::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<PaymentReceipt>
     */
    public function paymentReceipt(){

        return $this->hasMany(PaymentReceipt::class);
    }
    public function agencyUsers()
    {
        return $this->hasMany(AgencyUser::class);
    }

    

}
