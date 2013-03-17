<?php
namespace Application\CertificatsBundle\Menu;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware; 

class MenuBuilder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar navbar-fixed-top navbar-inverse');
        $item=$menu->addChild('Projects', array('route' => 'projets'))
            ->setAttribute('icon', 'icon-list');
        $item->setCurrent(true);
         $menu->addChild('applications', array('route' => 'applications'))
            ->setAttribute('icon', 'icon-list');
      /*  $menu->addChild('Employees', array('route' => 'projets_show'))
            ->setAttribute('icon', 'icon-group');*/
        return $menu;
    }
}