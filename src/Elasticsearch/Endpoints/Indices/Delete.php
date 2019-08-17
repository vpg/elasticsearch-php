<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Indices;

use Vpg\Elasticsearch\Common\Exceptions;
use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Delete
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Indices
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Delete extends AbstractEndpoint
{
    /**
     * @throws \Vpg\Elasticsearch\Common\Exceptions\RuntimeException
     */
    public function getURI(): string
    {
        if (isset($this->index) !== true) {
            throw new Exceptions\RuntimeException(
                'index is required for Delete'
            );
        }
        return "/{$this->index}";
    }

    public function getParamWhitelist(): array
    {
        return [
            'timeout',
            'master_timeout',
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards'
        ];
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }
}
