<?php

namespace KS\ServerBundle\User;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface AdvancedOAuth2UserInterface extends AdvancedUserInterface
{
   
    public function getScope();
}
