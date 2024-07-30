<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'terminal_id',
        'action',
        'read_at',
        'created_at',
        'document_detail_id'
    ];

    public function terminal() {
        return $this->hasMany(Terminal::class);
    }
}
