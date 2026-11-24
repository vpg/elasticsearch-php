<?php
namespace Elasticsearch\Tests\Connections;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\ServerErrorResponseException;
use Elasticsearch\Connections\Connection;
use Elasticsearch\Serializers\SerializerInterface;
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

    public function testConstructor()
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
        $this->assertInstanceOf(Connection::class, $connection);
    }

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
        $headers =  $connection->getHeaders();
        $this->assertArrayHasKey('User-Agent', $headers);
        $this->assertContains('elasticsearch-php/'. Client::VERSION, $headers['User-Agent'][0]);
    }

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
        $result = $connection->performRequest('GET', '/');
        $request = $connection->getLastRequestInfo()['request'];
        $this->assertArrayHasKey('User-Agent', $request['headers']);
        $this->assertContains('elasticsearch-php/'. Client::VERSION, $request['headers']['User-Agent'][0]);
    }

    public function testProcess5xxErrorNormalizesStringStatusBeforeBuildingException()
    {
        $this->expectNoPayloadLogging();
        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with('controlled response body', ['total_time' => 0])
            ->willReturn(false);
        $connection = $this->connection();

        try {
            $this->process5xxError($connection, '500', 'controlled response body');
            $this->fail('Expected a ServerErrorResponseException.');
        } catch (ServerErrorResponseException $exception) {
            $this->assertSame(500, $exception->getCode());
            $this->assertSame('controlled response body', $exception->getMessage());
        }
    }

    public function testProcess5xxErrorNormalizesNonStringBodyBeforeBuildingException()
    {
        $this->expectNoPayloadLogging();
        $responseBody = ['error' => 'controlled'];
        $encodedResponseBody = json_encode($responseBody);
        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($encodedResponseBody, ['total_time' => 0])
            ->willReturn(false);
        $connection = $this->connection();

        try {
            $this->process5xxError($connection, 500, $responseBody);
            $this->fail('Expected a ServerErrorResponseException.');
        } catch (ServerErrorResponseException $exception) {
            $this->assertSame(500, $exception->getCode());
            $this->assertSame($encodedResponseBody, $exception->getMessage());
        }
    }

    private function connection()
    {
        return new Connection(
            function () {
            },
            ['host' => 'localhost', 'scheme' => 'http'],
            [],
            $this->serializer,
            $this->logger,
            $this->trace
        );
    }

    private function expectNoPayloadLogging()
    {
        $this->logger->expects($this->never())->method('debug');
        $this->logger->expects($this->never())->method('warning');
        $this->trace->expects($this->never())->method('info');
        $this->trace->expects($this->never())->method('debug');
    }

    private function process5xxError(Connection $connection, $statusCode, $responseBody)
    {
        $method = new \ReflectionMethod(Connection::class, 'process5xxError');
        $method->setAccessible(true);

        $method->invoke(
            $connection,
            ['http_method' => 'GET', 'body' => null, 'headers' => []],
            [
                'status' => $statusCode,
                'body' => $responseBody,
                'transfer_stats' => ['total_time' => 0],
                'effective_url' => 'http://localhost/'
            ],
            []
        );
    }
}
