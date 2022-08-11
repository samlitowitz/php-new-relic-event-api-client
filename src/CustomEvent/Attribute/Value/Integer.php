<?php

namespace PhpNewRelic\CustomEvent\Attribute\Value;

use PhpNewRelic\CustomEvent\Attribute\Value;

final class Integer implements Value
{
	/** @var int */
	private $data;

	public function __construct(int $data)
	{
		$this->setData($data);
	}

	public function jsonSerialize()
	{
		return $this->getData();
	}

	public function getData(): int
	{
		return $this->data;
	}

	private function setData(int $data)
	{
		$this->data = $data;
	}
}
