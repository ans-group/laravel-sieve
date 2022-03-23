<?php

namespace Tests\Mocks;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
