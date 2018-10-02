<?php

namespace Vpg\Elasticsearch\Common\Exceptions;

/**
 * Missing404Exception
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Common\Exceptions
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Missing404Exception extends \Exception implements ElasticsearchException
{
}
