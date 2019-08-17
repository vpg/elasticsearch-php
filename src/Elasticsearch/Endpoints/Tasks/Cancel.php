<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Tasks;

use Vpg\Elasticsearch\Common\Exceptions;
use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Cancel
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Tasks
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Cancel extends AbstractEndpoint
{
    private $taskId;

    /**
     * @throws \Vpg\Elasticsearch\Common\Exceptions\InvalidArgumentException
     */
    public function setTaskId(?string $taskId): Cancel
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
        $taskId = $this->taskId ?? null;

        if (isset($taskId)) {
            return "/_tasks/$taskId/_cancel";
        }
        return "/_tasks/_cancel";
    }

    public function getParamWhitelist(): array
    {
        return [
            'nodes',
            'actions',
            'parent_task_id'
        ];
    }

    public function getMethod(): string
    {
        return 'POST';
    }
}
