<?php

namespace KS\DealBundle\Models;

use Symfony\Component\HttpFoundation\Request;
use KS\UserBundle\Entity\User;
use FOS\RestBundle\Request\ParamFetcher;

interface RelationManyHandlerInterface
{

    public function delete($entityLike, User $user);

    public function post($entityLike, User $user);

}