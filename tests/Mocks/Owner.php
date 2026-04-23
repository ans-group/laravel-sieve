<?php

declare(strict_types=1);

namespace Tests\Mocks;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    public function card()
    {
        return $this->hasMany(Card::class);
    }
}
