<?php

namespace KS\UserBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use KS\UserBundle\Document\User;
use FOS\RestBundle\Request\ParamFetcher;

interface UserHandlerInterface
{
	
    /**
     *
     * @return UserInterface
     */
    public function put(User $user,Request $request);


}