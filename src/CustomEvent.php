<?php

namespace PhpNewRelic;

use PhpNewRelic\CustomEvent\Attribute;
use PhpNewRelic\CustomEvent\AttributeCollection;

final class CustomEvent implements \JsonSerializable
{
	/** @var Attribute */
	private $eventType;
	/** @var AttributeCollection */
	private $attributes;

	private function __construct(Attribute $eventType, AttributeCollection $attributes)
	{
		$this->setEventType($eventType);
		$this->setAttributes($attributes);
	}

	public static function fromArray(string $eventType, array $attributes): self
	{
		$attributes = AttributeCollection::fromArray($attributes);
		$eventTypeAttribute = new CustomEvent\Attribute(
			new Attribute\Name(Attribute\Reserved::EVENT_TYPE),
			new Attribute\Value\String_($eventType)
		);
		$attributes->add($eventTypeAttribute);
		return new self($eventTypeAttribute, $attributes);
	}

	public function jsonSerialize()
	{
		return $this->getAttributes();
	}

	public function getEventType(): Attribute
	{
		return $this->eventType;
	}

	private function setEventType(Attribute $eventType): void
	{
		$this->eventType = $eventType;
	}

	public function getAttributes(): AttributeCollection
	{
		return $this->attributes;
	}

	private function setAttributes(AttributeCollection $attributes): void
	{
		$this->attributes = $attributes;
	}
}
