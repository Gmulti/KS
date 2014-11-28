<?php

namespace KS\ServerBundle\Storage;

use OAuth2\Storage\AuthorizationCodeInterface;
use KS\ServerBundle\Document\Client;

class AuthorizationCode implements AuthorizationCodeInterface
{
    private $mongo;

    public function __construct($mongo)
    {
        $this->mongo = $mongo;
    }

    public function getAuthorizationCode($code)
    {
        // Get Code
        $code = $this->mongo->getRepository('KSServerBundle:AuthorizationCode')->findOnyByCode($code);

        if (!$code) {
            return null;
        }

        return array(
            'client_id' => $code->getClient()->getClientId(),
            'user_id' => $code->getUserId(),
            'expires' => $code->getExpires()->getTimestamp(),
            'redirect_uri' => implode(' ', $code->getRedirectUri()),
            'scope' => $code->getScope()
        );
    }


    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
    {

        $client = $this->mongo->getRepository('KSServerBundle:Client')->findOneByClientId($client_id);

        if (!$client) throw new \Exception('Unknown client identifier');

        $authorizationCode = new \KS\ServerBundle\Document\AuthorizationCode();
        $authorizationCode->setCode($code);
        $authorizationCode->setClient($client);
        $authorizationCode->setUserId($user_id);
        $authorizationCode->setRedirectUri($redirect_uri);
        $authorizationCode->setExpires($expires);
        $authorizationCode->setScope($scope);

        $dm = $this->mongo->getManager();
        $dm->persist($authorizationCode);
        $dm->flush();
    }

    public function expireAuthorizationCode($code)
    {
        $code = $this->mongo->getRepository('KSServerBundle:AuthorizationCode')->findOneByCode($code);
        $dm = $this->mongo->getManager();
        $dm->remove($code);
        $dm->flush();
    }
}
