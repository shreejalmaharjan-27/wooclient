# A work in progress project to interact with Woocommerce REST API

## Usage
### Require composer autoload file and load classfiles
```php
use Shreejalmaharjan27\Wooclient\Category;
use Shreejalmaharjan27\Wooclient\Product;
use Shreejalmaharjan27\Wooclient\WooClient;


require __DIR__.'/vendor/autoload.php';
```

### Create WooClient Object (with trailing slash)
```php
$wooclient = new WooClient("ck_xxxxx,"cs_xxxxx","https://wordpress.example.com/");
```

### Create a Product
```php
$product = new Product($wooclient);
$product->create(name: 'Example Product', price: 18.00, image: 'https://example.com/image.jpg');
```

## Create a Product Category
```php
$category = new Category($wooclient);
$category->create(name: 'Example category');
```
