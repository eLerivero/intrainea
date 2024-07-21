<?php

namespace App\Models\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gerencias extends Model
{
    use HasFactory;

    protected $table = 'gerencias';
    protected $fillable = [

        'nombre'
    ];
}
