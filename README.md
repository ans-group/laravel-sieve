<img src="https://images.ukfast.co.uk/logos/ukfast/441x126_transparent_strapline.png" alt="UKFast Logo" width="350px" height="auto" />

# Laravel Sieve

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

## Contributing

We welcome contributions to this package that will be beneficial to the community.

You can reach out to our open-source team via **open-source@ukfast.co.uk** who will get back to you as soon as possible.

Please refer to our [CONTRIBUTING](CONTRIBUTING.md) file for more information.


## Security

If you think you have identified a security vulnerability, please contact our team via **security@ukfast.co.uk** who will get back to you as soon as possible, rather than using the issue tracker.


## Licence

This project is licenced under the MIT Licence (MIT). Please see the [Licence](LICENCE) file for more information.
