<?php

namespace KS\PageBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        if(isset($options['granted'])):
            return $this->mainDefaultMenu($menu,$options);
        else:
            return $this->defaultMenu($menu,$options);
        endif;

    }

    private function mainDefaultMenu($menu, $options){

        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Accueil', array('route' => 'komunity_store_index'));
        if(!$options['granted']):
            $menu->addChild('Connexion', array(
                    'route' => 'fos_user_security_login'
                )
            );
        
        else:
            $menu->addChild('Déconnecter', array(
                    'route' => 'fos_user_security_logout'
                )
            );

        endif;

        return $menu;
    }

    private function defaultMenu($menu,$options){

        $menu->addChild('Accueil', array('route' => 'komunity_store_index'));
        return $menu;
    }

    public function mainMenuConnect(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'komunity_store_index'));

        return $menu;
    }
}