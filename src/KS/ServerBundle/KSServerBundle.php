<?php

namespace KS\ServerBundle;

use KS\ServerBundle\DependencyInjection\Security\Factory\OAuthFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use KS\ServerBundle\DependencyInjection\Compiler\ServerCompilerPass;

class KSServerBundle extends Bundle
{
	public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OAuthFactory());
       $container->addCompilerPass(new ServerCompilerPass());
    }   

    public function getParent()
    {
        return 'OAuth2ServerBundle';
    }

}
