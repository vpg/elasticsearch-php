<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\ConnectionPool;

use Vpg\Elasticsearch\ConnectionPool\Selectors\SelectorInterface;
use Vpg\Elasticsearch\Connections\Connection;
use Vpg\Elasticsearch\Connections\ConnectionFactoryInterface;
use Vpg\Elasticsearch\Connections\ConnectionInterface;

class SimpleConnectionPool extends AbstractConnectionPool implements ConnectionPoolInterface
{

    /**
     * {@inheritdoc}
     */
    public function __construct($connections, SelectorInterface $selector, ConnectionFactoryInterface $factory, $connectionPoolParams)
    {
        parent::__construct($connections, $selector, $factory, $connectionPoolParams);
    }

    public function nextConnection(bool $force = false): ConnectionInterface
    {
        return $this->selector->select($this->connections);
    }

    public function scheduleCheck(): void
    {
    }
}
