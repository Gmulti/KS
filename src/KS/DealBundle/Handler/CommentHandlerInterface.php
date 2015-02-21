<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use KS\DealBundle\Entity\Category;
use FOS\RestBundle\Request\ParamFetcher;

interface CommentHandlerInterface
{
    public function put(Comment $comment,Request $request);

    public function post(Request $request);

}