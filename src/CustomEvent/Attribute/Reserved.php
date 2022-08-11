<?php

namespace PhpNewRelic\CustomEvent\Attribute;

final class Reserved
{
	public const ACCOUNT_ID = 'accountId';
	public const APP_ID = 'appId';
	public const ENTITY_GUID = 'entity.guid';
	public const EVENT_TYPE = 'eventType';
	public const FB_INPUT = 'fb.input';
	public const HOSTNAME = 'hostname';
	public const PLUGIN_TYPE = 'plugin.type';
	public const TIMESTAMP = 'timestamp';

	private static $reservedWords = [
		self::ACCOUNT_ID,
		self::APP_ID,
		self::ENTITY_GUID,
		self::EVENT_TYPE,
		self::FB_INPUT,
		self::HOSTNAME,
		self::PLUGIN_TYPE,
		self::TIMESTAMP,
	];

	public static function isReserved(string $word): bool
	{
		return in_array($word, self::$reservedWords);
	}
}
