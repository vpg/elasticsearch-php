<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Script;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;
use Vpg\Elasticsearch\Common\Exceptions;

/**
 * Class Put
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Script
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Put extends AbstractEndpoint
{
    /**
     * @var string
     */
    protected $context;

    public function setBody($body): Put
    {
        if (isset($body) !== true) {
            return $this;
        }

        $this->body = $body;

        return $this;
    }

    public function setContext(?string $context): Put
    {
        if ($context !== null) {
            $this->context = $context;
        }
        return $this;
    }

    /**
     * @throws \Vpg\Elasticsearch\Common\Exceptions\RuntimeException
     */
    public function getURI(): string
    {
        if (isset($this->id) !== true) {
            throw new Exceptions\RuntimeException(
                'id is required for Put'
            );
        }
        $id = $this->id;
        $context = $this->context ?? null;

        if (isset($context)) {
            return "/_scripts/$id/$context";
        }
        return "/_scripts/$id";
    }

    public function getParamWhitelist(): array
    {
        return [
            'timeout',
            'master_timeout',
            'context'
        ];
    }

    public function getMethod(): string
    {
        return 'PUT';
    }
}
