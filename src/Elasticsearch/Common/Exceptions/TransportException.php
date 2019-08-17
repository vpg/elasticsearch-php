<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Common\Exceptions;

/**
 * TransportException
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Common\Exceptions
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class TransportException extends \Exception implements ElasticsearchException
{
}
