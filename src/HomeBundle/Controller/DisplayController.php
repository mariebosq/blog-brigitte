<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Article;

class DisplayController extends Controller
{
    public function indexAction()
    {
        return $this->render('HomeBundle:Display:index.html.twig');
    }

    public function lifeAction()
    {
      return $this->render('HomeBundle:Display:life.html.twig');
    }

    public function writingAction()
    {
      return $this->render('HomeBundle:Display:writing.html.twig');
    }

    public function imagesAction()
    {
      $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('AdminBundle:Article');

        $query = $repository->createQueryBuilder('a')
          ->where("a.category = 'en-images'")
          ->andWhere('a.publishedAt IS NOT NULL')
          ->getQuery()
        ;

        $listArticles = $query->execute();

        return $this->render('HomeBundle:Display:images.html.twig', array(
          'listArticles' => $listArticles
        ));
    }

    public function eventAction()
    {
      $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('AdminBundle:Article');

      $query = $repository->createQueryBuilder('a')
        ->where("a.category = 'actualites'")
        ->andWhere('a.publishedAt IS NOT NULL')
        ->getQuery()
      ;

        $listArticles = $query->execute();

        return $this->render('HomeBundle:Display:event.html.twig', array(
          'listArticles' => $listArticles
        ));
    }

    public function newsAction()
    {
      $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('AdminBundle:Article');

        $query = $repository->createQueryBuilder('a')
          ->where("a.category = 'on-en-a-parle'")
          ->andWhere('a.publishedAt IS NOT NULL')
          ->getQuery()
        ;

        $listArticles = $query->execute();

        return $this->render('HomeBundle:Display:news.html.twig', array(
          'listArticles' => $listArticles
        ));
    }
}
