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
    public function writingAction()
    {
      return $this->render('HomeBundle:Default:writing.html.twig');
    }
    public function imagesAction()
    {
      return $this->render('HomeBundle:Default:images.html.twig');
    }
    public function eventAction()
    {
      return $this->render('HomeBundle:Default:event.html.twig');
    }
    public function newsAction()
    {
      return $this->render('HomeBundle:Default:news.html.twig');
    }
}
