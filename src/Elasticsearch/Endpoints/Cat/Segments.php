<?php

declare(strict_types = 1);
/**
 * User: zach
 * Date: 01/12/2015
 * Time: 14:34:49 pm
 */

namespace Vpg\Elasticsearch\Endpoints\Cat;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;
use Vpg\Elasticsearch\Common\Exceptions;

/**
 * Class Segments
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Cat
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */

class Segments extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/_cat/segments/$index";
        }

        return "/_cat/segments";
    }

    public function getParamWhitelist(): array
    {
        return [
            'format',
            'bytes',
            'h',
            'help',
            's',
            'v'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
