<?php

namespace KS\ServerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ServerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // Override Refresh Token Grant Type Settings
        $serviceId = 'oauth2.server';
        if ($container->hasDefinition($serviceId)) {
            $definition = $container->getDefinition($serviceId);

            $definition->replaceArgument(0, array(
                'access_lifetime' => 345600
            ));
        }
    }
}