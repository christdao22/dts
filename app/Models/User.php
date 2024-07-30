<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\DocumentDetail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'office_id',
        'email',
        'password',
        'is_admin',
        'is_dm',
        'can_view_all',
        'can_create'
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

    public function documentDetail(){
        return $this->hasMany(DocumentDetail::class);
    }

    public function documentTracking(){
        return $this->hasMany(DocumentTracking::class);
    }

    public function outGoing(){
        return $this->hasMany(Outgoing::class);
    }

    public function receivedHistory(){
        return $this->hasMany(ReceivedHistory::class);
    }

    public function documentTrace(){
        return $this->hasMany(DocumentTrace::class);
    }

    public function office() {
        return $this->belongsTo(Offices::class);
    }

    public function terminal() {
        return $this->hasOne(Terminal::class);
    }

    public function document_category() {
        return $this->hasOne(DocumentCategory::class);
    }
}
