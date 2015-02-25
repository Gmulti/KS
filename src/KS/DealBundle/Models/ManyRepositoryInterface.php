<?php

namespace KS\DealBundle\Models;

use KS\UserBundle\Entity\User;

interface ManyRepositoryInterface
{

    public function getManyByUser(ManyEntityInterface $entityMany, User $user, ManyTypeInterface $typeMany);

    public function getNbManyRelation(ManyEntityInterface $entityMany, ManyTypeInterface $typeMany, $options);


}