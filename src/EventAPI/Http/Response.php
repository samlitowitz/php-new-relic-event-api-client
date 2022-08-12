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

	public function __call(string $name, array $arguments)
	{
		if (!method_exists($this->response, $name)) {
			throw new \RuntimeException(
				sprintf(
					'failed calling method %s on object of class %s',
					$name,
					get_class($this->response)
				)
			);
		}
		return call_user_func_array([$this->response, $name], $arguments);
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
}
