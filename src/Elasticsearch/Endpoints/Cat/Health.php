<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Cat;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Health
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Cat
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Health extends AbstractEndpoint
{
    public function getURI(): string
    {
        return "/_cat/health";
    }

    public function getParamWhitelist(): array
    {
        return [
            'format',
            'local',
            'master_timeout',
            'h',
            'help',
            's',
            'ts',
            'v'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
