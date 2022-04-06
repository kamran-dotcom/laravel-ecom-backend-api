<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'selling_price',
        'original_price',
        'qty',
        'brand',
        'file',
        'feature',
        'popular',
        'status'
    ];

    protected $with = ['category'];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
