# Cin7 Core API V2 Client for PHP

A PHP client for interacting with the Cin7 Core (formerly DEAR Inventory) API V2.

## Installation

```bash
composer require trevweb/cin7-core-api-v2-client:^1.0
```

## Usage
### Initialise the client
```php
$accountId = 'your_account_id';
$applicationKey = 'your_application_key';
$client = new Cin7CoreApiV2Client($accountId, $applicationKey);
```
### Get a specific product
```php
try {
    $product = $client->get(Cin7CoreEndpoints::PRODUCT, ['Name' => 'Big Tyre']);
    print_r($product);
} catch (Exception $e) {
    // Handle exception
    echo "Error fetching product: " . $e->getMessage();
}
```
### Create a sale
```php
try {
    $saleData = [
            "CustomerID" => "guid-of-customer",
            "TaxRule" => "Tax on Sales",
            "Location" => "Main Warehouse",
            // ... other required fields
        ];
    
    $response = $client->post('sale', $saleData);
    echo "Created Sale ID: " . $response['ID'];
} catch (Exception $e) {
    echo "Error creating sale: " . $e->getMessage();
}
```
### Fetch all products (pagination)
```php
try {
    $products = $client->fetchAll(Cin7CoreEndpoints::PRODUCT, 'Products');
    foreach ($products as $product) {
        print_r($product);
    }
} catch (Exception $e) {
    echo "Error fetching products: " . $e->getMessage();
}
```

## Testing

This package uses PHPUnit for testing. To run the tests, first ensure you have installed the development dependencies:

```bash
composer install
```

Then, run the tests using the following command:

```bash
./vendor/bin/phpunit
```

The tests use a mocked version of the client to avoid making actual API calls.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
