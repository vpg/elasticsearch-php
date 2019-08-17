<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\ConnectionPool;

use Vpg\Elasticsearch\Connections\ConnectionInterface;

/**
 * ConnectionPoolInterface
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\ConnectionPool
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
interface ConnectionPoolInterface
{
    public function nextConnection(bool $force = false): ConnectionInterface;

    public function scheduleCheck(): void;
}
