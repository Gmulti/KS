<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use KS\DealBundle\Entity\Deal;
use FOS\RestBundle\Request\ParamFetcher;

interface DealHandlerInterface
{
    /**
     *
     * @return PostInterface
     */
    public function put(Deal $deal,Request $request);

    /**
     * Deal Deal, creates a new Deal.
     *
     * @param array $parameters
     *
     * @return PostInterface
     */
    public function post(Request $request);

}