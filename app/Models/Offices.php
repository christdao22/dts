<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offices extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_name',
    ];

    public function user() {
        return $this->hasMany(User::class);
    }

    public function office_terminal() {
        return $this->hasOne(OfficeTerminal::class);
    }
}
