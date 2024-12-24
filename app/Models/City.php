<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'country',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'city_user')
            ->withPivot('uv_threshold', 'precipitation_threshold')
            ->withTimestamps();
    }
}
