<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\Project;
class Category extends Model
{
    use HasFactory;
    use Notifiable;
    protected $primaryKey = "category_id";
    protected $fillable = ['name','description','photo'];
    public function projects()
    {
        return $this->hasMany('App\Models\Project', 'category_id','category_id');
    }
}
