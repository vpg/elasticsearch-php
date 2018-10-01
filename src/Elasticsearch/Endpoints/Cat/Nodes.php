<?php

namespace Vpg\Elasticsearch\Endpoints\Cat;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Nodes
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Cat
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Nodes extends AbstractEndpoint
{
    /**
     * @return string
     */
    public function getURI()
    {
        $uri   = "/_cat/nodes";

        return $uri;
    }

    /**
     * @return string[]
     */
    public function getParamWhitelist()
    {
        return array(
            'local',
            'master_timeout',
            'h',
            'help',
            'v',
            's',
            'full_id',
            'format',
        );
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return 'GET';
    }
}
