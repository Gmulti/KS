<?php

namespace KS\ServerBundle\User;

use Symfony\Component\Security\Core\User\UserInterface;

interface OAuth2UserInterface extends UserInterface
{

    public function getScope();
}
