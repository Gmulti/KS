<?php

namespace KS\PostBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use KS\PostBundle\Document\Post;
use FOS\RestBundle\Request\ParamFetcher;

interface PostHandlerInterface
{
    /**
     *
     * @return PostInterface
     */
    public function put(Post $post,Request $request);

    /**
     * Post Post, creates a new Post.
     *
     * @param array $parameters
     *
     * @return PostInterface
     */
    public function post(Request $request);

    public function get($params);
}