<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../cin7-example-api-credentials.php';

use Trevweb\Cin7CoreClient\Cin7CoreClient;
use Trevweb\Cin7CoreClient\Cin7CoreEndpoints;

$cin7Client = new Cin7CoreClient(
    CIN7_ACCOUNT_ID,
    CIN7_APPLICATION_KEY
);

try {
    $products = $cin7Client->fetchAll(
        Cin7CoreEndpoints::PRODUCT,
        'Products'
    );

    foreach ($products as $product) {
        echo "Product ID: {$product['ID']}, Name: {$product['Name']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
