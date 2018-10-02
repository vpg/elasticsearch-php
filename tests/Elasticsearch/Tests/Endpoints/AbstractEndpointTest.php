<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Tests\Endpoints;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;

class AbstractEndpointTest extends \PHPUnit\Framework\TestCase
{
    private $endpoint;

    public static function invalidParameters()
    {
        return [
            [['invalid' => 10]],
            [['invalid' => 10, 'invalid2' => 'another']],
        ];
    }

    /**
     * @dataProvider invalidParameters
     * @expectedException \Vpg\Elasticsearch\Common\Exceptions\UnexpectedValueException
     */
    public function testInvalidParamsCauseErrorsWhenProvidedToSetParams(array $params)
    {
        $this->endpoint->expects($this->once())
            ->method('getParamWhitelist')
            ->willReturn(['one', 'two']);

        $this->endpoint->setParams($params);
    }

    protected function setUp()
    {
        $this->endpoint = $this->getMockForAbstractClass(AbstractEndpoint::class);
    }
}
