<?php

namespace Vpg\Elasticsearch\ConnectionPool\Selectors;

/**
 * Class RandomSelector
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Connections\Selectors
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
interface SelectorInterface
{
    /**
     * Perform logic to select a single ConnectionInterface instance from the array provided
     *
     * @param  \Vpg\Elasticsearch\Connections\ConnectionInterface[] $connections an array of ConnectionInterface instances to choose from
     *
     * @return \Vpg\Elasticsearch\Connections\ConnectionInterface
     */
    public function select($connections);
}
