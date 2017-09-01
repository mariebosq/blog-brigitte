<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('HomeBundle:Default:index.html.twig');
    }

    public function lifeAction()
    {
      return $this->render('HomeBundle:Default:life.html.twig');
    }
}
