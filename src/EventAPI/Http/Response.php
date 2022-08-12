<?php

namespace PhpNewRelic\EventAPI\Http;

use http\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
	/** @var ResponseInterface */
	private $response;
	/** @var bool */
	private $success;
	/** @var string */
	private $uuid;

	public function __construct(ResponseInterface $response)
	{
		$this->setResponse($response);
	}

	public function getResponse(): ResponseInterface
	{
		return $this->response;
	}

	private function setResponse(ResponseInterface $response): void
	{
		$this->response = $response;
	}

	public function isSuccess(): bool
	{
		if ($this->success === null) {
			$this->decodeContent();
		}
		return $this->success ?? false;
	}

	private function setSuccess(bool $success): void
	{
		$this->success = $success;
	}

	public function getUuid(): string
	{
		if ($this->uuid === null) {
			$this->decodeContent();
		}
		return $this->uuid ?? '';
	}

	private function setUuid(string $uuid): void
	{
		$this->uuid = $uuid;
	}

	private function decodeContent(): void
	{
		$content = json_decode($this->getResponse()->getBody()->getContents(), true);
		if ($content === null) {
			throw new RuntimeException('Failed to parse response body');
		}
		[
			'success' => $success,
			'uuid' => $uuid,
		] = $content;
		if (is_bool($success)) {
			$this->setSuccess($success);
		}
		if (is_string($uuid)) {
			$this->setUuid($uuid);
		}
	}

	// ResponseInterface
	public function getStatusCode()
	{
		return $this->response->getStatusCode();
	}

	public function withStatus($code, $reasonPhrase = '')
	{
		return $this->response->withStatus($code, $http_response_header);
	}

	public function getReasonPhrase()
	{
		return $this->response->getReasonPhrase();
	}

	// MessageInterface
	public function getProtocolVersion()
	{
		return $this->response->getProtocolVersion();
	}

	public function withProtocolVersion($version)
	{
		return $this->response->withProtocolVersion($version);
	}

	public function getHeaders()
	{
		return $this->response->getHeaders();
	}

	public function hasHeader($name)
	{
		return $this->response->hasHeader($name);
	}

	public function getHeader($name)
	{
		return $this->response->getHeader($name);
	}

	public function getHeaderLine($name)
	{
		return $this->response->getHeaderLine($name);
	}

	public function withHeader($name, $value)
	{
		return $this->response->withHeader($name, $value);
	}

	public function withAddedHeader($name, $value)
	{
		return $this->response->withAddedHeader($name, $value);
	}

	public function withoutHeader($name)
	{
		return $this->response->withoutHeader($name);
	}

	public function getBody()
	{
		return $this->response->getBody();
	}

	public function withBody(StreamInterface $body)
	{
		return $this->response->withBody($body);
	}
}
