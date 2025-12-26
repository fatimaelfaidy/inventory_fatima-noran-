<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class part extends Model

{
    
       protected $fillable =
        [
            'name', 
            'stock',
             'status',
              'location'
        ];
 
    public function replacementItems ()
    {
    return 
    $this->hasOne(replacementItem::class) ;
    }
   
}


