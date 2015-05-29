<?php

namespace KS\UserBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use KS\UserBundle\Entity\User;

class RegisterListener
{
	protected $translator;
	protected $mailer;
	protected $router;
	protected $templating;

	public function __construct(Translator $translator = null, \Swift_Mailer $mailer, Router $router, $templating){
		$this->translator = $translator;
		$this->mailer = $mailer;
		$this->router = $router;
		$this->templating = $templating;
	}

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof User) {
   //      	var_dump('euuu');
   //      	$url = $router->generate('api_v1_users_delete_force_user', array(
			// 		'username' => $entity->getUsername(),
			// 		'token'    => $entity->getTokenForceDelete()
			// 		// '_format'  => 'json'
			// 	),
   //      		true
			// );
			// // var_dump($url);
			// var_dump('expression');
   //      	die();
    	    $message = \Swift_Message::newInstance()
	            ->setSubject($this->translator->trans('subject_email'))
	            ->setFrom('contact@komunitystore.com')
	            ->setTo('thomasdeneulin@gmail.com')
	            ->setBody($this->templating->render('KSUserBundle:Email:register.txt.twig', array(
		            		'url_delete' => 'truc'
		            	)
		            )
	            );
	         
	         $this->mailer->send($message);
        }

    }
}
