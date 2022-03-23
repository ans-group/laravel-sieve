<?php

namespace Tests\Mocks;

use Illuminate\Database\Eloquent\Model;
use UKFast\Sieve\Searchable;

class Pet extends Model implements Searchable
{
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function sieve($sieve)
    {
        return $sieve->configure(fn ($filter) => [
            'id' => $filter->numeric(),
            'name' => $filter->string(),
        ]);
    }
}
