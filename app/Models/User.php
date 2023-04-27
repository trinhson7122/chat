<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
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

    public function setTimeOnline()
    {
        $now = new Carbon();
        $this->last_online_at = $now->toDateTimeString();
        $this->save();
    }

    public function getShortName(): string
    {
        $arr = explode("-", convert_name($this->name));
        $shortName = $arr[0][0] . $arr[count($arr) - 1][0];
        return $shortName;
    }

    public function getUserWithShortName(): array
    {
        $arr = $this->toArray();
        $arr['short_name'] = $this->getShortName();
        return $arr;
    }
}
