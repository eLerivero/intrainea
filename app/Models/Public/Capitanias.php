<?php

namespace App\Models\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capitanias extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'siglas'
    ];
}
