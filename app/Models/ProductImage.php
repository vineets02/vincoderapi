<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "productimages";
    protected $primaryKey = "img_id";
    protected $fillable = ['images'];

    public function projects()
    {
        return $this->hasMany('App\Models\Project', 'images','images');
    }
}
