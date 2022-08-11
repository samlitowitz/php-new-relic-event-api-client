<?php

namespace PhpNewRelic\CustomEvent\Attribute;

final class Name implements \JsonSerializable
{
	/** @var string */
	private $name;

	public function __construct(string $name)
	{
		$this->setName($name);
	}

	public function jsonSerialize()
	{
		return $this->getName();
	}

	public function getName(): string
	{
		return $this->name;
	}

	private function setName(string $name)
	{
		$this->name = $name;
	}
}
