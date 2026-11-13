<?php

declare(strict_types = 1);

namespace Elasticsearch\Tests\Connections;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\ServerErrorResponseException;
use Elasticsearch\Connections\Connection;
use Elasticsearch\Serializers\SmartSerializer;
use GuzzleHttp\Ring\Future\CompletedFutureArray;
use Psr\Log\LoggerInterface;

/**
 * Error responses must always end as an Elasticsearch exception, whatever the shape of the body:
 * a PHP Error raised while building the exception kills the caller instead of failing one request.
 */
class ConnectionErrorResponseTest extends \PHPUnit\Framework\TestCase
{
    const VARNISH_503_BODY = "<!DOCTYPE html>\n<html><head><title>503 Backend fetch failed</title></head>"
        . "<body><h1>Error 503 Backend fetch failed</h1><p>Backend fetch failed</p></body></html>\n";

    const ELS_503_BODY = '{"error":{"root_cause":[{"type":"no_shard_available_action_exception",'
        . '"reason":"No shard available"}],"type":"no_shard_available_action_exception",'
        . '"reason":"No shard available"},"status":503}';

    public function testVarnish503HtmlBodyThrowsServerErrorResponseException()
    {
        $connection = $this->buildConnection(503, self::VARNISH_503_BODY, 'text/html; charset=utf-8');

        try {
            $connection->performRequest('GET', '/content_mapsobjects/_doc/1')->wait();
            $this->fail('A 503 response must throw');
        } catch (ServerErrorResponseException $e) {
            $this->assertSame(503, $e->getCode());
            $this->assertContains('Backend fetch failed', $e->getMessage());
        }
    }

    public function testJson5xxBodyThrowsServerErrorResponseException()
    {
        $connection = $this->buildConnection(503, self::ELS_503_BODY, 'application/json; charset=UTF-8');

        try {
            $connection->performRequest('GET', '/content_mapsobjects/_doc/1')->wait();
            $this->fail('A 503 response must throw');
        } catch (ServerErrorResponseException $e) {
            $this->assertSame(503, $e->getCode());
            $this->assertSame(self::ELS_503_BODY, $e->getMessage());
        }
    }

    public function testAlreadyDecodedJson5xxBodyThrowsServerErrorResponseException()
    {
        $body = json_decode(self::ELS_503_BODY, true);

        try {
            $this->invokeErrorProcessor('process5xxError', 503, $body);
            $this->fail('A 503 response must throw');
        } catch (ServerErrorResponseException $e) {
            $this->assertSame(503, $e->getCode());
            $this->assertSame(json_encode($body), $e->getMessage());
        }
    }

    /**
     * @dataProvider emptyBodyProvider
     */
    public function testEmpty5xxBodyThrowsServerErrorResponseException($body)
    {
        try {
            $this->invokeErrorProcessor('process5xxError', '502', $body);
            $this->fail('A 502 response must throw');
        } catch (ServerErrorResponseException $e) {
            $this->assertSame(502, $e->getCode());
            $this->assertSame('Unknown 502 error from Elasticsearch', $e->getMessage());
        }
    }

    public function emptyBodyProvider()
    {
        return [
            'null'         => [null],
            'empty string' => [''],
            'false'        => [false],
            'empty array'  => [[]],
        ];
    }

    public function testAlreadyDecoded404BodyThrowsMissing404Exception()
    {
        $body = ['_index' => 'content_mapsobjects', '_id' => '1', 'found' => false];

        try {
            $this->invokeErrorProcessor('process4xxError', 404, $body);
            $this->fail('A 404 response must throw');
        } catch (Missing404Exception $e) {
            $this->assertSame(404, $e->getCode());
            $this->assertSame(json_encode($body), $e->getMessage());
        }
    }

    private function buildConnection($status, $body, $contentType)
    {
        $handler = function () use ($status, $body, $contentType) {
            $stream = fopen('php://memory', 'r+');
            fwrite($stream, $body);
            rewind($stream);

            return new CompletedFutureArray([
                'status'         => $status,
                'body'           => $stream,
                'effective_url'  => 'http://localhost:9200/content_mapsobjects/_doc/1',
                'headers'        => [],
                'transfer_stats' => ['total_time' => 0.1, 'content_type' => $contentType],
            ]);
        };

        return new Connection(
            $handler,
            ['host' => 'localhost', 'scheme' => 'http'],
            [],
            new SmartSerializer(),
            $this->createMock(LoggerInterface::class),
            $this->createMock(LoggerInterface::class)
        );
    }

    private function invokeErrorProcessor($method, $status, $body)
    {
        $connection = $this->buildConnection(200, '', 'application/json');
        $request = ['http_method' => 'GET', 'body' => null, 'headers' => []];
        $response = [
            'status'         => $status,
            'body'           => $body,
            'effective_url'  => 'http://localhost:9200/content_mapsobjects/_doc/1',
            'transfer_stats' => ['total_time' => 0.1, 'content_type' => 'application/json'],
        ];

        $reflection = new \ReflectionMethod(Connection::class, $method);
        $reflection->setAccessible(true);
        $reflection->invoke($connection, $request, $response, []);
    }
}
