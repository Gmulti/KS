<?php

namespace KS\UserBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use KS\UserBundle\Entity\User;
use FOS\RestBundle\Request\ParamFetcher;

interface UserHandlerInterface
{
	
    /**
     *
     * @return UserInterface
     */
    public function put(User $user,Request $request);


}