<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Indices;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;
use Vpg\Elasticsearch\Common\Exceptions;

/**
 * Class Open
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Indices
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Open extends AbstractEndpoint
{
    /**
     * @throws \Vpg\Elasticsearch\Common\Exceptions\RuntimeException
     */
    public function getURI(): string
    {
        if (isset($this->index) !== true) {
            throw new Exceptions\RuntimeException(
                'index is required for Open'
            );
        }
        return "/{$this->index}/_open";
    }

    public function getParamWhitelist(): array
    {
        return [
            'timeout',
            'master_timeout',
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards',
            'wait_for_active_shards'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }
}
