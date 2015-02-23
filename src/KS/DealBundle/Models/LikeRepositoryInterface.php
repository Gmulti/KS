<?php

namespace KS\DealBundle\Models;

use KS\UserBundle\Entity\User;
use KS\DealBundle\Entity\Deal;

interface LikeRepositoryInterface
{

    public function getLikeByUser(Deal $deal, User $user);


}