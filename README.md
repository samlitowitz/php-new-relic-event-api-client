# PHP New Relic Event API Client

## Table of Contents

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Usage](#usage)
4. [References](#references)

## Introduction

This library introduces an [interface](src/EventAPI/Client.php) and an [implementation](src/EventAPI/Http/Client.php) to
interact with the [New Relic Event API][1].

### Interface

The interface is provided to allow drop-in replacement and composition.

```php
interface Client {
	public function send(PhpNewRelic\CustomEventCollection $customEvents): PhpNewRelic\EventAPI\Http\Response;
}
```

### Implementation

The only validation done client size is to ensure the payload is below the maximum allowed size.
Parsing errors will be handled by New Relic as described in the [documentation][2].

The client defaults to using the Guzzle HTTP client with a timeout of 12 seconds, however the constructor accepts
anything which implements the `Psr\Http\Client\ClientInterface` interface.

## Installation

```shell
composer require samlitowitz/php-new-relic-event-api-client
```

## Usage

```php
<?php

use PhpNewRelic\CustomEvent;
use PhpNewRelic\CustomEventCollection;
use PhpNewRelic\EventAPI\Http\Client;
use PhpNewRelic\EventAPI\Http\DomainName;
use PhpNewRelic\EventAPI\Http\Exception\GZipEncodingFailedException;
use PhpNewRelic\EventAPI\Http\Exception\PayloadExceedsMaximumSizeException;
use PhpNewRelic\EventAPI\Http\Exception\SubmissionErrorException;

define('NEW_RELIC_ACCOUNT_ID', '<YOUR-NEW-RELIC-ACCOUNT-ID>');
define('NEW_RELIC_API_KEY', '<YOUR-NEW-RELIC-API-KEY>');

// Instantiate the client
$client = new Client(NEW_RELIC_ACCOUNT_ID, NEW_RELIC_API_KEY, DomainName::US);

// Create custom event(s)
$events = CustomEventCollection::fromArray([
	CustomEvent::fromArray(
		'yourCustomEventType',
		[
			new CustomEvent\Attribute(
				new CustomEvent\Attribute\Name('stringAttr'),
				new CustomEvent\Attribute\Value\String_('string')
			),
			new CustomEvent\Attribute(
				new CustomEvent\Attribute\Name('floatAttr'),
				new CustomEvent\Attribute\Value\String_(2.1)
			),
			new CustomEvent\Attribute(
				new CustomEvent\Attribute\Name('intAttr'),
				new CustomEvent\Attribute\Value\String_(2)
			),
		]
	),
	// ...
]);
try {
	$response = $client->send($events);
} catch (GZipEncodingFailedException $e) {
	// Handle a failure to encode here
} catch (PayloadExceedsMaximumSizeException $e) {
	// Handle a too large payload side here
} catch (SubmissionErrorException $e) {
	// Handle submission errors here
} catch (Throwable $t) {
	// Handle other throws here
}
```

## References

1. [New Relic Event API Documentation][1]

[1]: https://docs.newrelic.com/docs/data-apis/ingest-apis/event-api/introduction-event-api

[2]: https://docs.newrelic.com/docs/data-apis/ingest-apis/event-api/introduction-event-api/#errors-parsing
