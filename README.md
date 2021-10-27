## Laravel Sieve

A library to handle filtering and sortring for your APIs

Filters like `/pets?name:in=Snoopy,Hobbes` will be applied to your query builder instance and only results matching that criteria will return

Sieve uses an expressive API to configure these filters:


```php
<?php

class Pet extends Model implements Searchable
{
    public function sieve(Sieve $sieve)
    {
        $sieve->configure(fn ($filter) => [
           'name' => $filter->string(),
           'breed' => $filter->enum(['Beagle', 'Tiger']),
        ]);
    }
}
```

```php
<?php

class PetController extends Controller
{
    public function index()
    {
        return Pets::query()->search()->paginate();
    }
}
```
