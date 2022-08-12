<?php

namespace PhpNewRelic\EventAPI\Http\Exception;

use PhpNewRelic\EventAPI\Http\Exception;

final class PayloadExceedsMaximumSizeException extends Exception
{
	/** @var int */
	private $max;
	/** @var int */
	private $given;
	public function __construct(int $max, int $given)
	{
		$this->setMax($max);
		$this->setGiven($given);
		$this->message = sprintf('Payload exceeds maximum size of %d bytes: %d bytes provided', $this->max, $this->given);
	}

	public function getMax(): int
	{
		return $this->max;
	}

	private function setMax(int $max): void
	{
		$this->max = $max;
	}

	public function getGiven(): int
	{
		return $this->given;
	}

	private function setGiven(int $given): void
	{
		$this->given = $given;
	}
}
