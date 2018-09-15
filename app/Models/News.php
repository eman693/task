<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Super;

class News extends Model
{
    protected $table = 'news';
    protected $fillable = ['date','title','short_desc','text'];

    public function getIdAttribute($value)
    {
        return (int)$value;
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
