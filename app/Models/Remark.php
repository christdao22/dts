<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    use HasFactory;

    protected $fillable = [
        'remarks',
    ];

    public function documentTracking() {
        return $this->hasOne(DocumentTracking::class);
    }

    public function receivedHistory() {
        return $this->hasOne(ReceivedHistory::class);
    }

    public function outgoing() {
        return $this->hasOne(Outgoing::class);
    }
}
