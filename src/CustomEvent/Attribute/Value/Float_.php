<?php

namespace PhpNewRelic\CustomEvent\Attribute\Value;

use PhpNewRelic\CustomEvent\Attribute\Value;

final class Float_ implements Value
{
	/** @var float */
	private $data;

	public function __construct(float $data)
	{
		$this->setData($data);
	}

	public function jsonSerialize()
	{
		return $this->getData();
	}

	public function getData(): float
	{
		return $this->data;
	}

	private function setData(float $data)
	{
		$this->data = $data;
	}
}
