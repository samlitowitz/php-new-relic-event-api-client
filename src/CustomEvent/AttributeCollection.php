<?php

namespace PhpNewRelic\CustomEvent;

final class AttributeCollection implements \Countable, \Iterator, \JsonSerializable
{
	private $items = [];
	private $iter;

	public static function fromArray(array $items = []): self
	{
		$collection = new self();
		foreach ($items as $item) {
			$collection->add($item);
		}
		return $collection;
	}

	public function count(): int
	{
		return \count($this->items);
	}

	public function jsonSerialize()
	{
		return $this->toArray();
	}

	public function toArray(): array
	{
		return $this->items;
	}

	public function add(Attribute ...$entities): void
	{
		\array_push($this->items, ...$entities);
	}

	public function current(): ?Attribute
	{
		if ($this->iter === null) {
			return null;
		}
		if (!\array_key_exists($this->iter, $this->items)) {
			return null;
		}
		return $this->items[$this->iter];
	}

	public function next()
	{
		if (!$this->valid()) {
			return;
		}
		$this->iter++;
	}

	public function key(): ?int
	{
		return $this->iter;
	}

	public function rewind(): void
	{
		if ($this->count() === 0) {
			$this->iter = null;
			return;
		}
		$this->iter = 0;
	}

	public function valid(): bool
	{
		return $this->current() !== null;
	}
}
