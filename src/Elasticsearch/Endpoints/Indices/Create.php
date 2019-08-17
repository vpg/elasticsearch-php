<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Indices;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;
use Vpg\Elasticsearch\Common\Exceptions;

/**
 * Class Create
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Indices
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Create extends AbstractEndpoint
{
    /**
     * @param  array|object $body
     * @throws \Vpg\Elasticsearch\Common\Exceptions\InvalidArgumentException
     */
    public function setBody($body): Create
    {
        if (isset($body) !== true) {
            return $this;
        }

        $this->body = $body;

        return $this;
    }

    /**
     * @throws \Vpg\Elasticsearch\Common\Exceptions\RuntimeException
     */
    public function getURI(): string
    {
        if (isset($this->index) !== true) {
            throw new Exceptions\RuntimeException(
                'index is required for Create'
            );
        }
        return "/{$this->index}";
    }

    public function getParamWhitelist(): array
    {
        return [
            'include_type_name',
            'wait_for_active_shards',
            'timeout',
            'master_timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }
}
