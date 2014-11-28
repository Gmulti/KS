<?php

namespace KS\ServerBundle\Manager;


class ScopeManager implements ScopeManagerInterface
{
    private $mongo;

    public function __construct($mongo)
    {
        $this->mongo = $mongo;
    }

    /**
     * Creates a new scope
     *
     * @param string $scope
     *
     * @param string $description
     *
     * @return Scope
     */
    public function createScope($scope, $description = null)
    {
        $scopeObject = new \KS\ServerBundle\Document\Scope();
        $scopeObject->setScope($scope);
        $scopeObject->setDescription($description);

        // Store Scope
        $dm = $this->mongo->getManager();
        $dm->persist($scopeObject);
        $dm->flush();

        return $scopeObject;
    }

    /**
     * Find a single scope by the scope
     *
     * @param $scope
     * @return Scope
     */
    public function findScopeByScope($scope)
    {
        $scopeObject = $this->mongo->getRepository('KSServerBundle:Scope')->findOneByScope($scope);

        return $scopeObject;
    }

    /**
     * Find all the scopes by an array of scopes
     *
     * @param array $scopes
     * @return mixed|void
     */
    public function findScopesByScopes(array $scopes)
    {
        $scopeObjects = $this->mongo->getRepository('KSServerBundle:Scope')
            ->createQueryBuilder('a')
            ->where('a.scope in (?1)')
            ->setParameter(1, implode(',', $scopes))
            ->getQuery()->getResult();

        return $scopeObjects;
    }
}
