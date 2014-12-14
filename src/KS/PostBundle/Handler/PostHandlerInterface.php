<?php

namespace KS\PostBundle\Handler;

use Symfony\Component\HttpFoundation\Request;

interface PostHandlerInterface
{
    /**
     * Get a Post given the identifier
     *
     * @param int $id
     *
     * @return PostInterface
     */
    public function get($id);

    /**
     * Post Post, creates a new Post.
     *
     * @param array $parameters
     *
     * @return PostInterface
     */
    public function post(Request $parameters);
}