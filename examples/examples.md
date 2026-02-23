# Cin7 Client Examples

This section provides examples of how to use the Cin7 client library for various tasks.
* [Fetch All](./fetchall/cin7-example-fetchall.php) - Fetch all data from a paginated endpoint.
* [Export](./export-products/cin7-example-export.php) - Export data from a paginated endpoint.

## Credentials
Create a file in the examples directory called `cin7-example-api-credentials.php` and add the following:
```php
<?php
DEFINE('CIN7_ACCOUNT_ID', 'your-id-here');
DEFINE('CIN7_APPLICATION_KEY', 'your-key-here');
```
Replace the values with your own credentials from the Cin7 dashboard.