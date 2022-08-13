<?php

namespace PhpNewRelic\Tests\EventAPI\Http;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PhpNewRelic\CustomEvent;
use PhpNewRelic\CustomEventCollection;
use PhpNewRelic\EventAPI\Http\Client;
use PhpNewRelic\EventAPI\Http\DomainName;
use PhpNewRelic\EventAPI\Http\Exception\SubmissionErrorException;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase {
	private const INTEGRATION_TESTING_ACCOUNT_ID = -1;
	private const INTEGRATION_TESTING_API_KEY = '';

	public function testSendSuccess(): void
	{
		$accountId = uniqid();
		$apiKey = uniqid();
		$expectedUuid = uniqid();

		$mock = new MockHandler([
			new Response(200, ['Content-Type' => 'application/json'], json_encode(['success' => true, 'uuid' => $expectedUuid])),
		]);
		$handlerStack = HandlerStack::create($mock);
		$guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
		$client = new Client($accountId, $apiKey, DomainName::US, $guzzleClient);

		$events = CustomEventCollection::fromArray([
			CustomEvent::fromArray(
				'sendSuccess',
				[
					new CustomEvent\Attribute(
						new CustomEvent\Attribute\Name('stringAttr'),
						new CustomEvent\Attribute\Value\String_('string')
					),
					new CustomEvent\Attribute(
						new CustomEvent\Attribute\Name('floatAttr'),
						new CustomEvent\Attribute\Value\String_(2.1)
					),
					new CustomEvent\Attribute(
						new CustomEvent\Attribute\Name('intAttr'),
						new CustomEvent\Attribute\Value\String_(2)
					),
				]
			),
		]);
		$response = $client->send($events);
		$this->assertTrue($response->isSuccess());
		$this->assertEquals($expectedUuid, $response->getUuid());
	}

	/**
	 * @dataProvider sendSubmissionErrorProvider
	 */
	public function testSendSubmissionError(SubmissionErrorException $expectedException): void
	{
		$accountId = uniqid();
		$apiKey = uniqid();

		$mock = new MockHandler([
			$expectedException->getResponse(),
		]);
		$handlerStack = HandlerStack::create($mock);
		$guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);
		$client = new Client($accountId, $apiKey, DomainName::US, $guzzleClient);

		$events = CustomEventCollection::fromArray([
			CustomEvent::fromArray(
				'sendSuccess',
				[
					new CustomEvent\Attribute(
						new CustomEvent\Attribute\Name('stringAttr'),
						new CustomEvent\Attribute\Value\String_('string')
					),
					new CustomEvent\Attribute(
						new CustomEvent\Attribute\Name('floatAttr'),
						new CustomEvent\Attribute\Value\String_(2.1)
					),
					new CustomEvent\Attribute(
						new CustomEvent\Attribute\Name('intAttr'),
						new CustomEvent\Attribute\Value\String_(2)
					),
				]
			),
		]);
		$this->expectException(SubmissionErrorException::class);
		$this->expectExceptionCode($expectedException->getCode());
		$this->expectExceptionMessage($expectedException->getMessage());
		$client->send($events);
	}

	public function sendSubmissionErrorProvider(): array
	{
		return [
			'Status Code: 401' => [
				new SubmissionErrorException(
					new Request('POST', uniqid()),
					new \PhpNewRelic\EventAPI\Http\Response(new Response(401))
				),
			],
			'Status Code: 403' => [
				new SubmissionErrorException(
					new Request('POST', uniqid()),
					new \PhpNewRelic\EventAPI\Http\Response(new Response(403))
				),
			],
			'Status Code: 408' => [
				new SubmissionErrorException(
					new Request('POST', uniqid()),
					new \PhpNewRelic\EventAPI\Http\Response(new Response(408))
				),
			],
			'Status Code: 413' => [
				new SubmissionErrorException(
					new Request('POST', uniqid()),
					new \PhpNewRelic\EventAPI\Http\Response(new Response(413))
				),
			],
			'Status Code: 415' => [
				new SubmissionErrorException(
					new Request('POST', uniqid()),
					new \PhpNewRelic\EventAPI\Http\Response(new Response(415))
				),
			],
			'Status Code: 429' => [
				new SubmissionErrorException(
					new Request('POST', uniqid()),
					new \PhpNewRelic\EventAPI\Http\Response(new Response(429))
				),
			],
			'Status Code: 503' => [
				new SubmissionErrorException(
					new Request('POST', uniqid()),
					new \PhpNewRelic\EventAPI\Http\Response(new Response(503))
				),
			],
		];
	}

	public function testSendIntegrationSuccess(): void
	{
		$this->markTestSkipped('Skipping integration test by default');
		$events = CustomEventCollection::fromArray([
			CustomEvent::fromArray(
				'sendIntegrationSuccess',
				[
					new CustomEvent\Attribute(
						new CustomEvent\Attribute\Name('isTest'),
						new CustomEvent\Attribute\Value\String_('true')
					),
				]
			),
		]);
		$client = new Client(self::INTEGRATION_TESTING_ACCOUNT_ID, self::INTEGRATION_TESTING_API_KEY, DomainName::US);
		$client->send($events);
		$this->assertTrue(true);
	}
}
