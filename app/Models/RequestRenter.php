<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class RequestRenter extends Model
{
    use HasFactory;
    protected $fillable=[
        "status",
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
