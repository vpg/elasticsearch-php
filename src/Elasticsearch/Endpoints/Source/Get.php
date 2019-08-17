<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Source;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;
use Vpg\Elasticsearch\Common\Exceptions;

/**
 * Class Get
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Source
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Get extends AbstractEndpoint
{
    /**
     * @throws \Vpg\Elasticsearch\Common\Exceptions\RuntimeException
     */
    public function getURI(): string
    {
        if (isset($this->id) !== true) {
            throw new Exceptions\RuntimeException(
                'id is required for Get'
            );
        }
        if (isset($this->index) !== true) {
            throw new Exceptions\RuntimeException(
                'index is required for Get'
            );
        }

        $id = $this->id;
        $index = $this->index;
        $type = $this->type ?? null;

        if (isset($type)) {
            return "/$index/$type/$id/_source";
        }
        return "/$index/_source/$id";
    }

    public function getParamWhitelist(): array
    {
        return [
            'parent',
            'preference',
            'realtime',
            'refresh',
            'routing',
            '_source',
            '_source_excludes',
            '_source_includes',
            'version',
            'version_type'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
