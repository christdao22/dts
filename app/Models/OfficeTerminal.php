<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeTerminal extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'terminal_id'
    ];

    public function terminal() {
        return $this->belongsTo(Terminal::class);
    }

    public function office() {
        return $this->belongsTo(Offices::class);
    }
}
