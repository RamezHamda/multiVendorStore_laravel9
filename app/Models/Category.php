<?php

namespace App\Models;

use App\Models\Product;
use App\Rules\FilterRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory ,SoftDeletes;

    protected $fillable = [
        'name', 'parent_id', 'description', 'image', 'status', 'slug'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id')
            ->withDefault([
                'name' => '-'
            ]);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

    public function scopeFilter(Builder $builder, $filters)
    {

        $builder->when($filters['name'] ?? false, function($builder, $value) {
            $builder->where('categories.name', 'LIKE', "%{$value}%");
        });

        $builder->when($filters['status'] ?? false, function($builder, $value) {
            $builder->where('categories.status', '=', $value);
        });

    }

    public static function ruels($id =0){
    return [
            // 'name' => [
            //     new FilterRule(['ramez','ahmed','issa'])
            // ],
            'name' =>"required|string|min:3|max:255|unique:categories,name,$id|filter:php,laravel,html",
            'parent_id' => 'nullable|int:exists:categories,id',
            'image' => 'image|max:1048576|dimensions:min_width=100,min_height=100',
            'status' => 'required|in:active,archived',

        ];
    }
}
