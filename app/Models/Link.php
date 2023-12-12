<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Visits()
    {
        return $this->hasMany(Visit::class);
    }
}
