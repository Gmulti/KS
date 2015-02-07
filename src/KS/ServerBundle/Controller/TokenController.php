<?php

namespace KS\ServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use OAuth2\ServerBundle\Controller\TokenController as BaseController;

class TokenController extends BaseController
{
    /**
     *
     * @Route("/token", name="_token")
     */
    public function tokenAction()
    {
        $server = $this->get('oauth2.server');

        // Add Grant Types
        $server->addGrantType($this->get('oauth2.grant_type.client_credentials'));
        $server->addGrantType($this->get('oauth2.grant_type.authorization_code'));
        $server->addGrantType($this->get('oauth2.grant_type.refresh_token'));
        $server->addGrantType($this->get('oauth2.grant_type.user_credentials'));

        return $server->handleTokenRequest($this->get('oauth2.request'), $this->get('oauth2.response'));
    }
}
