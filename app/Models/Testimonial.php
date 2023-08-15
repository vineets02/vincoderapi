<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    protected $table = "testimonials";
    protected $primaryKey = "testimonials_id";
    protected $fillable = ['name','review','photo'];
}
