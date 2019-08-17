<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Tasks;

use Vpg\Elasticsearch\Common\Exceptions;
use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Get
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Tasks
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Get extends AbstractEndpoint
{
    private $taskId;

    public function setTaskId(?string $taskId): Get
    {
        if (isset($taskId) !== true) {
            return $this;
        }

        $this->taskId = $taskId;

        return $this;
    }

    /**
     * @throws \Vpg\Elasticsearch\Common\Exceptions\RuntimeException
     */
    public function getURI(): string
    {
        if (isset($this->taskId) !== true) {
            throw new Exceptions\RuntimeException(
                'task_id is required for Get'
            );
        }

        return "/_tasks/{$this->taskId}";
    }

    public function getParamWhitelist(): array
    {
        return [
            'wait_for_completion',
            'timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
