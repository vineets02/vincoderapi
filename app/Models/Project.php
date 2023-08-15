<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
class Project extends Model
{
    use HasFactory;

    protected $table = "projects";
    protected $primaryKey = "projects_id";
    public $fillable = [
        'name', 'description', 'photo', 'category_id', 'images', 'skills', 'project_link'
    ];

    protected $casts = [
        'skills' => 'array' // Cast the 'skills' column to an array
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function projectImages()
    {
        return $this->hasMany(ProductImage::class, 'project_id', 'projects_id');
    }
}
