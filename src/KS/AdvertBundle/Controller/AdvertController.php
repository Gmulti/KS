<?php

namespace KS\AdvertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\Query\ResultSetMapping;
use KS\DealBundle\Entity\Deal;

class AdvertController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirect('http://www.komunitystore.com');
    }
}
