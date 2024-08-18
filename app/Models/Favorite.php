<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Favorite extends Model
{
    use HasFactory;

    protected $fillable=[
        "post_id",
        "renter_id",
    ];

    public function post():BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    public function renter():BelongsTo
    {
        return $this->belongsTo(Renter::class);
    }
}
