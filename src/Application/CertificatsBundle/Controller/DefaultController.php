<?php

namespace Application\CertificatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApplicationCertificatsBundle:Default:index.html.twig', array('name' => $name));
    }
}
