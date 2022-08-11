<?php

namespace PhpNewRelic\CustomEvent\Attribute;

final class Reserved {
	public const EVENT_TYPE = 'eventType';

	private static $reservedWords = [
		self::EVENT_TYPE,
	];

	public static function isReserved(string $word): bool
	{
		return in_array($word, self::$reservedWords);
	}
}
