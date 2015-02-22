<?php

namespace KS\DealBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;
use KS\DealBundle\Entity\Comment;
use KS\DealBundle\Entity\Deal;

interface CommentHandlerInterface
{
    public function put(Comment $comment,Request $request);

    public function post(Deal $deal, Request $request);

}