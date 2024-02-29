<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outgoing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_detail_id',
        'office_id',
        'terminal_id',
        'remark_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function documentDetail(){
        return $this->belongsTo(DocumentDetail::class);
    }

    public function terminal() {
        return $this->belongsTo(Terminal::class);
    }

    public function remark() {
        return $this->belongsTo(Remark::class);
    }
}
