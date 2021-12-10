<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;
    protected $fillable=[
        "name","surname","email","phone"
    ];
    public function appointments(){
        return $this->hasMany(Appointments::class);
    }
}
