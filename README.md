# fgoro

PHP gateway for emitting invoices through FGO API v2 (`POST https://api.fgo.ro/v2/facturi`).
Requires PHP 8.0 or newer.

## Configuration

```php
use Invoicetic\Fgoro\FgoroGateway;

$gateway = new FgoroGateway();
$gateway->initialize([
    'login_id' => getenv('FGO_LOGIN_ID'),     // Company code: Fgo-Cod-Unic
    'secret_key' => getenv('FGO_SECRET_KEY'), // v2 API key: Authorization: Bearer
]);

$response = $gateway->createInvoice($invoice)->send();
if (!$response->isSuccessful()) {
    throw new RuntimeException($response->getStatusCode() . ': ' . $response->getMessage());
}

$issuedInvoice = $response->getInvoice();
```

`$invoice` is an `Invoicetic\Common\Dto\Invoice\Invoice`; see `examples/create.php`.

## Migrating from v1

The PHP credential setters and invoice DTO interface are unchanged, but `secret_key`
must now contain a **v2 API key**, not the old hash secret. Credentials are sent only
in HTTP headers. The request body uses snake_case fields and `client.tip` remains
`PJ` or `PF`. `platforma_url` is no longer required or transmitted.

Responses retain the v2 `data`/`meta` envelope in `getData()`. Invoice series and number
are read from `data.serie` and `data.numar`. `isSuccessful()` uses the actual HTTP
status (2xx), not the v1 `Success` field. HTTP errors, including 400, 403, 404 and 429,
return unsuccessful response objects; `getStatusCode()` exposes the HTTP status,
`getMessage()` exposes problem `detail` (or `title`), and `getCode()` exposes the stable
problem `type`. Full problem fields remain available through `getData()`.
Transport failures and invalid JSON responses throw exceptions.

The legacy v1 sandbox is not used. `sandbox => true` throws an explicit configuration
error; for a provider-confirmed v2 test service, configure `endpoint` directly with
its base URL (including `/v2`) instead.

Only invoice creation is implemented by this library. Other invoice operations,
lists/cursor pagination and the additional v2 resources are not currently exposed.

Migration reference: https://api.fgo.ro/v2/docs/ghiduri/migrare-de-la-v1