# Eloquent Filter

[![Latest Stable Version](https://poser.pugx.org/buibr/eloquent-filter/v/stable)](https://packagist.org/packages/buibr/eloquent-filter)
[![Total Downloads](https://poser.pugx.org/buibr/eloquent-filter/downloads)](https://packagist.org/packages/buibr/eloquent-filter)
[![License](https://poser.pugx.org/buibr/eloquent-filter/license)](https://packagist.org/packages/buibr/eloquent-filter)

`eloquent-filter` is a simple package for filtering Eloquent queries based on request query parameters with predefined methods per parameter. It allows you to easily build complex query filters for your Laravel applications.

## Installation

You can install the package via composer:

```bash
composer require buibr/eloquent-filter
```

## Usage

### Step 1: Add `HasFilters` Trait to Your Model

Include the `HasFilters` trait in any Eloquent model where you want to use query filters.

```php
use BI\EloquentFilter\HasFilters;

class YourModel extends Model
{
    use HasFilters;

    // Other model code...
}
```

### Step 2: Create a Filter Class

Create a new filter class that extends `QueryFilter`. In this class, define methods for each query parameter you want to filter by. Each method should accept the parameter value and modify the query accordingly.

```php
use BI\EloquentFilter\QueryFilter;

class YourModelFilter extends QueryFilter
{
    public function status($value)
    {
        return $this->builder->where('status', $value);
    }

    public function category($value)
    {
        return $this->builder->where('category_id', $value);
    }

    // Add more filter methods as needed...
}
```

### Step 3: Apply the Filter in Your Controller

In your controller, apply the filter to the query by using the `filter` scope. Pass an instance of your filter class as the argument.

```php
use App\Models\YourModel;
use App\Filters\YourModelFilter;

class YourController extends Controller
{
    public function index(YourModelFilter $filters)
    {
        $items = YourModel::filter($filters)->get();

        return response()->json($items);
    }
}
```

### Example Request

You can now filter the query by passing parameters in the request URL:

```
GET /your-models?status=active&category=1
```

This will apply the `status` and `category` filters based on the methods defined in your `YourModelFilter` class.

## Customizing Filters

To customize how filters are applied, simply add more methods to your filter class. Each method corresponds to a query parameter and can modify the Eloquent query as needed.

```php
class YourModelFilter extends QueryFilter
{
    public function created_at($value)
    {
        return $this->builder->whereDate('created_at', $value);
    }

    public function sort($value)
    {
        return $this->builder->orderBy('created_at', $value);
    }
}
```

## Contributing

Contributions are welcome! If you encounter any issues or have suggestions for improvements, please open an issue or submit a pull request.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
