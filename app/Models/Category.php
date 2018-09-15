<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name'];

    public function getIdAttribute($value)
    {
        return (int)$value;
    }
    public function news()
    {
        return $this->belongsToMany(News::class);
    }
}
