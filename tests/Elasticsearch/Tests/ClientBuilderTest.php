<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Tests;

use Vpg\Elasticsearch\ClientBuilder;
use Vpg\Elasticsearch\Common\Exceptions\InvalidArgumentException;
use Vpg\Elasticsearch\Tests\ClientBuilder\DummyLogger;
use PHPUnit\Framework\TestCase;

class ClientBuilderTest extends TestCase
{
    /**
     * @expectedException TypeError
     */
    public function testClientBuilderThrowsExceptionForIncorrectLoggerClass()
    {
        ClientBuilder::create()->setLogger(new DummyLogger);
    }

    /**
     * @expectedException TypeError
     */
    public function testClientBuilderThrowsExceptionForIncorrectTracerClass()
    {
        ClientBuilder::create()->setTracer(new DummyLogger);
    }
}
