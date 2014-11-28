<?php

namespace KS\ServerBundle\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use FOS\UserBundle\Security\UserProvider;

use FOS\UserBundle\Model\UserManagerInterface;
use KS\UserBundle\Document\User;

class OAuth2UserProvider extends UserProvider implements UserProviderInterface
{
    private $mongo;

    public function __construct($mongo, UserManagerInterface $userManager)
    {
        parent::__construct($userManager);
        $this->mongo = $mongo;
    }

   
    public function supportsClass($class)
    {
        if ($class == 'OAuth2User') {
            return true;
        }

        return false;
    }

}
