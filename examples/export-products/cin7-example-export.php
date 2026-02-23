<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../cin7-example-api-credentials.php';

use Trevweb\Cin7CoreClient\Cin7CoreClient;
use Trevweb\Cin7CoreClient\Cin7CoreEndpoints;

$cin7Client = new Cin7CoreClient(
    CIN7_ACCOUNT_ID,
    CIN7_APPLICATION_KEY
);

const EXPORT_PATH = '/tmp/cin7-products';

try {
    $products = $cin7Client->fetchAll(
        Cin7CoreEndpoints::PRODUCT,
        'Products'
    );

    if (!file_exists(EXPORT_PATH)) {
        mkdir(EXPORT_PATH);
    }

    foreach ($products as $product) {
        $id = $product['ID'];
        file_put_contents(
            EXPORT_PATH . "/product_{$id}.json",
            json_encode($product, JSON_PRETTY_PRINT)
        );
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}