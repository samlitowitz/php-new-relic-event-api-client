<?php

namespace PhpNewRelic\CustomEvent\Attribute\Value;

use PhpNewRelic\CustomEvent\Attribute\Value;

final class String_ implements Value
{
	/** @var string */
	private $data;

	public function __construct(string $data)
	{
		$this->setData($data);
	}

	public function jsonSerialize()
	{
		return $this->getData();
	}

	public function getData(): string
	{
		return $this->data;
	}

	private function setData(string $data)
	{
		$this->data = $data;
	}
}
