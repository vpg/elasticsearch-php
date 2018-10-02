<?php

namespace Vpg\Elasticsearch\Endpoints\Template;

use Vpg\Elasticsearch\Endpoints\AbstractEndpoint;
use Vpg\Elasticsearch\Common\Exceptions;

/**
 * Class Delete
 *
 * @category Elasticsearch
 * @package  Vpg\Elasticsearch\Endpoints\Template
 * @author   Zachary Tong <zach@elastic.co>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache2
 * @link     http://elastic.co
 */
class Delete extends AbstractEndpoint
{
    /**
     * @throws \Vpg\Elasticsearch\Common\Exceptions\RuntimeException
     * @return string
     */
    public function getURI()
    {
        if (isset($this->id) !== true) {
            throw new Exceptions\RuntimeException(
                'id is required for Delete'
            );
        }
        $templateId   = $this->id;
        $uri  = "/_search/template/$templateId";

        return $uri;
    }

    /**
     * @return string[]
     */
    public function getParamWhitelist()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return 'DELETE';
    }
}
