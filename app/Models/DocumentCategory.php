<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_name',
        'user_id',
        'is_direct',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function document_detail(){
        return $this->hasOne(DocumentDetail::class);
    }
}
