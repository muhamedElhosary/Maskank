<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;




class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'images',
        'description',
        'price',
        'size',
        'purpose',
        'bedrooms',
        'bathrooms',
        'region',
        'city',
        'floor',
        'condition',
        'status',
        'booked',
        'owner_id',
        'admin_id',


    ];

    protected $searchable = [
       'images',
        'description',
        'price',
        'size',
        'purpose',
        'bedrooms',
        'bathrooms',
        'region',
        'city',
        'floor',
        'condition',
        'status',
        'booked',
        'owner_id',
        'owner.username',
        'owner.owner_name',
        'owner.email',
        'owner.phone',
    ];

    public function owner():BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function requests()
    {
        return $this->hasMany(RequestRenter::class, 'post_id');
    }

    public function ScopeSearch(Builder $builder ,string $term=''){


        $builder->where(function ($query) use ($term) {
            foreach ($this->searchable as $searchable) {
                if (Str::contains($searchable, '.')) {
                    $relation = Str::beforeLast($searchable, '.');
                    $column = Str::afterLast($searchable, '.');
                    $query->orWhereHas($relation, function ($relationQuery) use ($column, $term) {
                        $relationQuery->where($column, 'like', "%$term%");
                    });
                } else {
                    $query->orWhere($searchable, 'like', "%$term%");
                }
            }
        });
        return $builder;

    }


}
