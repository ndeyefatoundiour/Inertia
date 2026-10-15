<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apprenant extends Model
{
    protected $fillable = ['nomComplet', 'sexe', 'niveau', 'groupe_id'];

    protected $casts = [
        'niveau' => 'integer',
    ];

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }
}