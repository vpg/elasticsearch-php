<?php

namespace Vpg\Elasticsearch\Common\Exceptions;

/**
 * Unauthorized401Exception, thrown on 401 unauthorized http error
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Common\Exceptions
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Unauthorized401Exception extends \Exception implements ElasticsearchException
{
}
