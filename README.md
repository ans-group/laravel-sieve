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

## Filters

Filters are done in the query parameters with the format `property:operator=term`, for example

 * `name:eq=Bob` - WHERE name = 'Bob'
 * `age:lt=20` - WHERE age < 10
 * `employed:eq=true` - WHERE employed = 1

By default, if no operator is specified, it will default to `eq` so `name=Bob` will expand to `name:eq=Bob`

## Sorting

Sieve will also allow consumers of your API to specify sort order. You can do this by `sort=property:direction`

 * `sort=age:asc`
 * `sort=id:desc`

## Available Filters


### String

The basic filter. Ideal for textual data, implements `eq`, `neq`, `in`, and `nin`

```php
<?php
$filter->string()
```

### Numeric

Ideal for numerical data, on top of the basic operators, it also provides `lt` (less than) and `gt` (greater than)

```php
<?php
$filter->numeric()
```

### Enum

Same as the string filter but with extra validation. Will throw an exception if the user gives an invalid value

```php
<?php
$filter->enum(['HR', 'RnD'])
```


### Boolean

Only provides `eq` and `neq`. Also takes two arguments to specify what your true and false values are in the DB

```php
<?php
$filter->boolean() // defaults to 1 and 0
```

### Date

Provides the same operations as numeric

```php
<?php
$filter->date(),
```

You can get type hinting for these filters by specifying `FilterBuilder` when using the configure method

```php
<?php
$sieve->configure(fn (FilterBuilder $filter) [
    'name' => $filter->string(),
]);
```

## Relationships

You can filter on relationships using the `for` method on the filter builder

```php
<?php
$sieve->configure(fn ($filter) => [
    'owner_name' => $filter->for('owner.name')->string()
])
```

You can also use the `for` method if your API properties don't match column names in the database, for example

```php
<?php
$sieve->configure(fn ($filter) => [
    'date_created' => $filter->for('created_at')->date()
])
```


## Contributing

We welcome contributions to this package that will be beneficial to the community.

You can reach out to our open-source team via **open-source@ukfast.co.uk** who will get back to you as soon as possible.

Please refer to our [CONTRIBUTING](CONTRIBUTING.md) file for more information.


## Security

If you think you have identified a security vulnerability, please contact our team via **security@ukfast.co.uk** who will get back to you as soon as possible, rather than using the issue tracker.


## Licence

This project is licenced under the MIT Licence (MIT). Please see the [Licence](LICENCE) file for more information.
