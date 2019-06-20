<?php

namespace BandejaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        // return $this->render('BandejaBundle:Default:index.html.twig');
        return $this->redirect('BandejaBundle:Bandeja:index');
    }
}
