<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Indices;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class ForceMerge
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Indices
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class ForceMerge extends AbstractEndpoint
{
    public function getURI(): string
    {
        $index = $this->index ?? null;

        if (isset($index)) {
            return "/$index/_forcemerge";
        }
        return "/_forcemerge";
    }

    public function getParamWhitelist(): array
    {
        return [
            'flush',
            'ignore_unavailable',
            'allow_no_indices',
            'expand_wildcards',
            'max_num_segments',
            'only_expunge_deletes'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }
}
