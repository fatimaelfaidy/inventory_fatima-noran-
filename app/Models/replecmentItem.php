<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class replecmentItem extends Model
{
     protected $fillable = [
        'name',
        'stock',
        'stock',
        'loction'
    ];
    public function part()
{
    return $this->belongsTo(Part::class);
}
 
}
