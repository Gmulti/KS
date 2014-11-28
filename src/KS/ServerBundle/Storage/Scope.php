<?php

namespace KS\ServerBundle\Storage;

use OAuth2\Storage\ScopeInterface;
use KS\ServerBundle\Manager\ScopeManagerInterface;

class Scope implements ScopeInterface
{
    private $mongo;

    /**
     * @var ScopeManagerInterface
     */
    private $sm;

    public function __construct($mongo, ScopeManagerInterface $scopeManager)
    {
        $this->mongo = $mongo;
        $this->sm = $scopeManager;
    }


    public function scopeExists($scope, $client_id = null)
    {
        $scopes = explode(' ', $scope);
        if ($client_id) {
            // Get Client
            $client = $this->mongo->getRepository('KSServerBundle:Client')->findOneByClientId($client_id);

            if (!$client) {
                return false;
            }

            $valid_scopes = $client->getScopes();

            foreach ($scopes as $scope) {
                if (!in_array($scope, $valid_scopes)) {
                    return false;
                }
            }

            return true;
        }

        $valid_scopes = $this->sm->findScopesByScopes($scopes);

        return count($valid_scopes) == count($scopes);
    }

    public function getDefaultScope($client_id = null)
    {
        return false;
    }

    public function getDescriptionForScope($scope)
    {
        // Get Scope
        $scopeObject = $this->sm->findScopeByScope($scope);

        if (!$scopeObject) {
            return $scope;
        }

        return $scopeObject->getDescription();
    }
}
