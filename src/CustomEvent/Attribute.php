<?php

namespace PhpNewRelic\CustomEvent;

use PhpNewRelic\CustomEvent\Attribute\Name;
use PhpNewRelic\CustomEvent\Attribute\Value;

final class Attribute implements \JsonSerializable
{
	/** @var Name */
	private $name;
	/** @var Value */
	private $value;

	public function __construct(Name $name, Value $value)
	{
		$this->setName($name);
		$this > $this->setValue($value);
	}

	public function jsonSerialize()
	{
		return [
			$this->getName()->jsonSerialize() => $this->getValue()
		];
	}

	public function getName()
	{
		return $this->name;
	}

	private function setName($name)
	{
		$this->name = $name;
	}

	public function getValue()
	{
		return $this->value;
	}

	private function setValue($value)
	{
		$this->value = $value;
	}
}
