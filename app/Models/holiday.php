<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class holiday extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'date', 'type', 'iso_3166'];
}
