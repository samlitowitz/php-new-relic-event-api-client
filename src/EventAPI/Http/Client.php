<?php

namespace PhpNewRelic\EventAPI\Http;

use GuzzleHttp\Psr7\Request;
use PhpNewRelic\CustomEventCollection;
use PhpNewRelic\EventAPI\Http\Exception\GZipEncodingFailedException;
use PhpNewRelic\EventAPI\Http\Exception\PayloadExceedsMaximumSizeException;
use PhpNewRelic\EventAPI\Http\Exception\SubmissionErrorException;
use Psr\Http\Client\ClientInterface;

final class Client implements \PhpNewRelic\EventAPI\Client
{
	public const MAX_PAYLOAD_SIZE_IN_BYTES = 10 ** 6;
	/** @var string */
	private $accountId;
	/** @var string */
	private $apiKey;
	/** @var string */
	private $domainName;
	/** @var ClientInterface */
	private $httpClient;

	public function __construct(
		string $accountId,
		string $apiKey,
		string $domainName,
		ClientInterface $httpClient = null
	) {
		$this->setAccountId($accountId);
		$this->setApiKey($apiKey);
		$this->setDomainName($domainName);
		$this->setHttpClient($httpClient ?: new \GuzzleHttp\Client(['timeout' => 12]));
	}

	public function send(CustomEventCollection $customEvents): void
	{
		$body = gzencode(json_encode($customEvents), 9);
		if ($body === false) {
			throw new GZipEncodingFailedException();
		}
		if (strlen($body) > self::MAX_PAYLOAD_SIZE_IN_BYTES) {
			throw new PayloadExceedsMaximumSizeException(self::MAX_PAYLOAD_SIZE_IN_BYTES, strlen($body));
		}
		$url = sprintf('https://%s/v1/accounts/%s/events', $this->getDomainName(), $this->getAccountId());
		$request = new Request(
			'POST',
			$url,
			[
				'Api-Key' => $this->getApiKey(),
				'Content-Type' => 'application/json',
				'Content-Encoding' => 'gzip',
			],
			$body
		);
		$response = $this->getHttpClient()->sendRequest($request);

		switch ($response->getStatusCode()) {
			case 200:
				$responseBody = json_decode($response->getBody()->getContents(), true);

				break;
			default:
				throw new SubmissionErrorException($request, $response);
		}
	}

	public function getAccountId(): string
	{
		return $this->accountId;
	}

	private function setAccountId(string $accountId): void
	{
		$this->accountId = $accountId;
	}

	public function getApiKey(): string
	{
		return $this->apiKey;
	}

	private function setApiKey(string $apiKey): void
	{
		$this->apiKey = $apiKey;
	}

	public function getDomainName(): string
	{
		return $this->domainName;
	}

	private function setDomainName(string $domainName): void
	{
		$this->domainName = $domainName;
	}

	public function getHttpClient(): ClientInterface
	{
		return $this->httpClient;
	}

	private function setHttpClient(ClientInterface $httpClient): void
	{
		$this->httpClient = $httpClient;
	}
}
