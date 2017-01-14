<?php

namespace Naweown;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'description',
        'slug'
    ];
    
    public function scopeFindBySlug(Builder $builder, string $cat)
    {
        return $builder->where('slug', $cat)
            ->firstOrFail();
    }
}
