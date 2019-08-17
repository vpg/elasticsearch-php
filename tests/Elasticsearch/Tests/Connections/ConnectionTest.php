<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Tests\Connections;

use Vpg\Elasticsearch\Client;
use Vpg\Elasticsearch\ClientBuilder;
use Vpg\Elasticsearch\Connections\Connection;
use Vpg\Elasticsearch\Serializers\SerializerInterface;
use Psr\Log\LoggerInterface;

class ConnectionTest extends \PHPUnit\Framework\TestCase
{
    private $logger;
    private $trace;
    private $serializer;

    protected function setUp()
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->trace = $this->createMock(LoggerInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
    }

    /**
     * @covers \Connection
     */
    public function testConstructor()
    {
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            function () {
            },
            $host,
            [],
            $this->serializer,
            $this->logger,
            $this->trace
        );

        $this->assertInstanceOf(Connection::class, $connection);
    }

    /**
     * @depends testConstructor
     *
     * @covers \Connection::getHeaders
     */
    public function testGetHeadersContainUserAgent()
    {
        $params = [];
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            function () {
            },
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );

        $headers = $connection->getHeaders();

        $this->assertArrayHasKey('User-Agent', $headers);
        $this->assertContains('elasticsearch-php/'. Client::VERSION, $headers['User-Agent'][0]);
    }

    /**
     * @depends testGetHeadersContainUserAgent
     *
     * @covers \Connection::getHeaders
     * @covers \Connection::performRequest
     * @covers \Connection::getLastRequestInfo
     */
    public function testUserAgentHeaderIsSent()
    {
        $params = [];
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey('User-Agent', $request['headers']);
        $this->assertContains('elasticsearch-php/'. Client::VERSION, $request['headers']['User-Agent'][0]);
    }

    /**
     * @depends testConstructor
     *
     * @covers \Connection::getHeaders
     * @covers \Connection::performRequest
     * @covers \Connection::getLastRequestInfo
     */
    public function testGetHeadersContainsHostArrayConfig()
    {
        $host = [
            'host' => 'localhost',
            'user' => 'foo',
            'pass' => 'bar',
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            [],
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey(CURLOPT_HTTPAUTH, $request['client']['curl']);
        $this->assertArrayHasKey(CURLOPT_USERPWD, $request['client']['curl']);
        $this->assertArrayNotHasKey('Authorization', $request['headers']);
        $this->assertContains('foo:bar', $request['client']['curl'][CURLOPT_USERPWD]);
    }

    /**
     * @depends testGetHeadersContainsHostArrayConfig
     *
     * @covers \Connection::getHeaders
     * @covers \Connection::performRequest
     * @covers \Connection::getLastRequestInfo
     */
    public function testGetHeadersContainApiKeyAuth()
    {
        $params = ['client' => ['headers' => [
            'Authorization' => [
                'ApiKey ' . base64_encode(sha1((string)time()))
            ]
        ] ] ];
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey('Authorization', $request['headers']);
        $this->assertArrayNotHasKey(CURLOPT_HTTPAUTH, $request['headers']);
        $this->assertContains($params['client']['headers']['Authorization'][0], $request['headers']['Authorization'][0]);
    }

    /**
     * @depends testGetHeadersContainApiKeyAuth
     *
     * @covers \Connection::getHeaders
     * @covers \Connection::performRequest
     * @covers \Connection::getLastRequestInfo
     */
    public function testGetHeadersContainApiKeyAuthOverHostArrayConfig()
    {
        $params = ['client' => ['headers' => [
            'Authorization' => [
                'ApiKey ' . base64_encode(sha1((string)time()))
            ]
        ] ] ];
        $host = [
            'host' => 'localhost',
            'user' => 'foo',
            'pass' => 'bar',
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey('Authorization', $request['headers']);
        $this->assertArrayNotHasKey(CURLOPT_HTTPAUTH, $request['headers']);
        $this->assertContains($params['client']['headers']['Authorization'][0], $request['headers']['Authorization'][0]);
    }

    /**
     * @depends testGetHeadersContainsHostArrayConfig
     *
     * @covers \Connection::getHeaders
     * @covers \Connection::performRequest
     * @covers \Connection::getLastRequestInfo
     */
    public function testGetHeadersContainBasicAuth()
    {
        $params = ['client' => ['curl' => [
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD  => 'username:password',
        ] ] ];
        $host = [
            'host' => 'localhost'
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey(CURLOPT_HTTPAUTH, $request['client']['curl']);
        $this->assertArrayHasKey(CURLOPT_USERPWD, $request['client']['curl']);
        $this->assertArrayNotHasKey('Authorization', $request['headers']);
        $this->assertContains($params['client']['curl'][CURLOPT_USERPWD], $request['client']['curl'][CURLOPT_USERPWD]);
    }

    /**
     * @depends testGetHeadersContainBasicAuth
     *
     * @covers \Connection::getHeaders
     * @covers \Connection::performRequest
     * @covers \Connection::getLastRequestInfo
     */
    public function testGetHeadersContainBasicAuthOverHostArrayConfig()
    {
        $params = ['client' => ['curl' => [
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD  => 'username:password',
        ] ] ];
        $host = [
            'host' => 'localhost',
            'user' => 'foo',
            'pass' => 'bar',
        ];

        $connection = new Connection(
            ClientBuilder::defaultHandler(),
            $host,
            $params,
            $this->serializer,
            $this->logger,
            $this->trace
        );
        $result  = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];

        $this->assertArrayHasKey(CURLOPT_HTTPAUTH, $request['client']['curl']);
        $this->assertArrayHasKey(CURLOPT_USERPWD, $request['client']['curl']);
        $this->assertArrayNotHasKey('Authorization', $request['headers']);
        $this->assertContains('username:password', $request['client']['curl'][CURLOPT_USERPWD]);
    }
}
