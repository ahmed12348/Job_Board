<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'website',
        'description',
        'logo_url',
        'location'
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
