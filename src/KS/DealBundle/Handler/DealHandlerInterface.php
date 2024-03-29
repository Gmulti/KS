<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use KS\DealBundle\Entity\Deal;
use FOS\RestBundle\Request\ParamFetcher;

interface DealHandlerInterface
{

    public function put(Deal $deal,Request $request);

    public function post(Request $request);

}