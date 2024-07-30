<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Terminal extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'terminal_name',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function office_terminal() {
        return $this->hasMany(OfficeTerminal::class);
    }

    public function document_detail() {
        return $this->hasOne(DocumentDetail::class);
    }

    public function document_tracking() {
        return $this->hasOne(DocumentTracking::class);
    }

    public function outgoings() {
        return $this->hasMany(Terminal::class);
    }

    public function notifications() {
        return $this->belongsTo(Notification::class);
    }
}
