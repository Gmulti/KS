<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use KS\DealBundle\Entity\Category;
use FOS\RestBundle\Request\ParamFetcher;

interface CategoryHandlerInterface
{
    public function put(Category $deal,Request $request);

    public function post(Request $request);

}