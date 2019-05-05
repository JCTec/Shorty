<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class URL extends Model
{
    protected $table = 'urls';

    protected $primaryKey = 'id';

    protected $fillable = [
        'url', 'shorty',
    ];


}
