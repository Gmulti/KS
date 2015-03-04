<?php

namespace KS\DealBundle\Models;

use Symfony\Component\HttpFoundation\Request;
use KS\UserBundle\Entity\User;
use FOS\RestBundle\Request\ParamFetcher;

interface RelationManyHandlerInterface
{
	public function get(ManyEntityInterface $entityMany, $options);

    public function delete(ManyEntityInterface $entityLike, User $user, $options = array());

    public function post(ManyEntityInterface $entityLike, User $user, $options = array());

}