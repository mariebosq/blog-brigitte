<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Article;

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
      $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('AdminBundle:Article')
      ;

      $listArticles = $repository->findBy(array('slug' => 'enimages'));

      return $this->render('HomeBundle:Default:images.html.twig', array(
        'listArticles' => $listArticles
      ));

    }

    public function eventAction()
    {
      $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('AdminBundle:Article')
      ;

      $listArticles = $repository->findBy(array('slug' => 'event'));

      return $this->render('HomeBundle:Default:event.html.twig', array(
        'listArticles' => $listArticles
      ));
    }

    public function newsAction()
    {
      $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('AdminBundle:Article')
      ;

      $listArticles = $repository->findBy(array('slug' => 'actualites'));

      return $this->render('HomeBundle:Default:news.html.twig', array(
        'listArticles' => $listArticles
      ));
    }
}
