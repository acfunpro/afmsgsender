<?php

namespace acfunpro\afmsgsender\service;

abstract class Service
{
    /**
     * Constructs request parameter
     *
     * @param array $params
     * @return array
     */
    abstract public function createParams(array $params);

    /**
     * Request service
     *
     * @param array $params
     * @return mixed
     */
    abstract public function request(array $params);

    /**
     * Resolve the response data
     *
     * @param array $params
     * @return array
     */
    abstract public function response(array $params);

}
