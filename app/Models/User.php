<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Billable;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }
    public function getRouteKeyName()
    {
        return 'id';
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'social_id',
        'social_type',
        'email_verified_at',
        'company_id',
        'stripe_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
    public function company()
    {
        return $this->hasOne(Company::class, 'user_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }
    public function role()
    {
        return $this->hasOne(UsersRole::class, 'user_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'user_id', 'role_id');
    }
    public function completes()
    {
        return $this->HasMany(Respondent::class, 'user_id', 'id')->where('status', 'complete');
    }
    public function terminates()
    {
        return $this->HasMany(Respondent::class, 'user_id', 'id')->where('status', 'terminate');
    }
    public function quotafull()
    {
        return $this->HasMany(Respondent::class, 'user_id', 'id')->where('status', 'terminate');
    }
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'chat_participants', 'user_id', 'conversation_id');
    }
    public function conversations()
    {
        return $this->belongsToMany(ChatConversation::class, 'chat_participants', 'user_id', 'conversation_id');
    }

    public function stripePlan()
    {
        return $this->hasOne(Plan::class, 'stripe_plan_id', 'stripe_id');
    }

    /**
     * stripeUser
     *
     * @return void
     */
    public function stripeUser()
    {
        return $this->hasOne(StripeUser::class, 'user_id', 'id');
    }
}
