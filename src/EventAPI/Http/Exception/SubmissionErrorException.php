<?php

namespace PhpNewRelic\EventAPI\Http\Exception;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpNewRelic\EventAPI\Http\Exception;

final class SubmissionErrorException extends Exception
{
	/** @var Request */
	private $request;
	/** @var Response */
	private $response;

	public function __construct(Request $request, Response $response)
	{
		$this->setRequest($request);
		$this->setResponse($response);
		$this->setMessageFromResponse();
	}

	public function getRequest(): Request
	{
		return $this->request;
	}

	private function setRequest(Request $request): void
	{
		$this->request = $request;
	}

	public function getResponse(): Response
	{
		return $this->response;
	}

	private function setResponse(Response $response): void
	{
		$this->response = $response;
	}

	private function setMessageFromResponse(): void
	{
		switch ($this->response->getStatusCode()) {
			case 400:
				$this->message = 'Missing or invalid content length: Unable to process empty request.';
				break;
			case 403:
				$this->message = 'Missing or invalid key: Invalid license key. Register a valid license key.';
				break;
			case 408:
				$this->message = 'Request timed out: Request took too long to process.';
				break;
			case 413:
				$this->message = 'Content too large: Request is too large to process. Refer to the limits and restricted characters to troubleshoot.';
				break;
			case 415:
				$this->message = 'Invalid content type: Must be application/JSON. The Event API accepts any content type except multi-part/related and assumes it can be parsed to JSON.';
				break;
			case 429:
				$this->message = 'Too many requests due to rate limiting.';
				break;
			case 503:
				$this->message = 'Service temporarily unavailable: Retry request';
				break;
			default:
				$this->message = 'Unknown error, status code ' . $this->response->getStatusCode();
		}
	}
}
