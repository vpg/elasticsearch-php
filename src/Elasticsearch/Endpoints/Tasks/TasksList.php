<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints\Tasks;

use Vpg\Elasticsearch\Common\Exceptions;
use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class List
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Tasks
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class TasksList extends AbstractEndpoint
{

    /**
     * @throws \Vpg\Elasticsearch\Common\Exceptions\RuntimeException
     */
    public function getURI(): string
    {
        return "/_tasks";
    }

    public function getParamWhitelist(): array
    {
        return [
            'nodes',
            'actions',
            'detailed',
            'parent_task_id',
            'wait_for_completion',
            'group_by',
            'timeout'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }
}
