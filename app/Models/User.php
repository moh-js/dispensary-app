<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class User extends Authenticatable implements ContractsAuditable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    use Auditable;

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }

    public function scopeGetUsers($query)
    {
        $query
        ->withTrashed()
        ->where([['id', '!=', auth()->id()], ['id', '!=', 1]]);
    }

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return User::withTrashed()->where('username', $value)->first();
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    // protected static function booted()
    // {
    //     static::addGlobalScope('fromStation', function (Builder $builder) {
    //         $builder->where('station_id', request()->user()->station_id);
    //     });
    // }
}
