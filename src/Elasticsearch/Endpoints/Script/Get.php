<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Script;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;
use Vpg\Elasticsearch\Common\Exceptions;

/**
 * Class Get
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Script
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
        return "/_scripts/{$this->id}";
    }

    public function getParamWhitelist(): array
    {
        return [
            'master_timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
