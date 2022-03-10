# php-mcm-api-wrapper
[![GitHub license](https://img.shields.io/badge/license-MIT-007ec6)](https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

A PHP wrapper for MC-Market's [HTTP API](https://www.mc-market.org/wiki/ultimate-api/).

- Full coverage of the API.
- Type hinting used for IDE autocomplete & readability.
- Requests are dynamically stalled to stay within rate limiting rules.
- No dependencies other than cURL - clone this repository and go!

## Usage

An extensive list of [examples](https://github.com/Majored/php-mcm-api-wrapper/tree/main/examples) can be found under the `/examples` directory.

```PHP
<?php
require __DIR__ . "/../src/APIWrapper.php";

$token = new APIToken(TokenType::PRIVATE, "Find @ https://www.mc-market.org/account/api");
$wrapper = new APIWrapper();

if ($error = $wrapper->initialise($token)->getError()) {
    die("API initialisation error: ". $error["message"]);
}

echo $wrapper->members()->fetch(87939)->getData()["username"];
```

## Issues & Support
Whether you're wanting to report a bug you've come across during use of this wrapper or are seeking general help/assistance, please utilise the [issues tracker](https://github.com/Majored/php-mcm-api-wrapper/issues) and tag your issue appropriately during creation.

I try to respond to issues within a reasonable timeframe.
