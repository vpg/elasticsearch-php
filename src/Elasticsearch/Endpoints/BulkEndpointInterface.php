<?php

declare(strict_types = 1);

namespace Vpg\Elasticsearch\Endpoints;

use Vpg\Elasticsearch\Serializers\SerializerInterface;

/**
 * Interface BulkEndpointInterface
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
interface BulkEndpointInterface
{
    /**
     * Constructor
     *
     * @param SerializerInterface $serializer A serializer
     */
    public function __construct(SerializerInterface $serializer);
}
