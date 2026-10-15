<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    protected $fillable = ['numero', 'niveau'];

    protected $casts = [
        'niveau' => 'integer',
    ];

    public function apprenants()
    {
        return $this->hasMany(Apprenant::class);
    }
}