<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Article;
use HomeBundle\Entity\Comment;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
        ->getManager();

        // $query = $repository->createQueryBuilder('a')
        //   ->where("a.category = 'en-images'")
        //   ->andWhere('a.publishedAt IS NOT NULL')
        //   ->getQuery()
        // ;
        $query = "
          SELECT articles.id,
                 articles.title,
                 articles.content,
                 articles.published_at,
                 (
                   SELECT COUNT(*)
                   FROM comments
                   WHERE comments.article_id = articles.id
                 ) AS nb_comments
          FROM articles
          WHERE articles.published_at IS NOT NULL
          AND articles.category = 'en-images'
        ";

        $stmt = $repository->getConnection()->prepare($query);
        $stmt->execute();
        $listArticles = $stmt->fetchAll();

        for ($i = 0; $i < count($listArticles); $i++)
        {
          //on coupe l'affichage de l'article à 255 caractères
          $listArticles[$i]['content'] = substr($listArticles[$i]['content'], 55, 255);

          //on ajoute trois petits points lorsque le mot ou la phrase est coupée
          if (strlen($listArticles[$i]['content']) === 255) {
            $listArticles[$i]['content'] = $listArticles[$i]['content'] . "...";
          }

        }

        return $this->render('HomeBundle:Display:images.html.twig', array(
          'listArticles' => $listArticles
        ));
    }

    public function eventAction()
    {
      $repository = $this
        ->getDoctrine()
        ->getManager();

      // $query = $repository->createQueryBuilder('a')
      //   ->where("a.category = 'actualites'")
      //   ->andWhere('a.publishedAt IS NOT NULL')
      //   ->getQuery()
      // ;

      $query = "
        SELECT articles.id,
               articles.title,
               articles.content,
               articles.published_at,
               (
                 SELECT COUNT(*)
                 FROM comments
                 WHERE comments.article_id = articles.id
               ) AS nb_comments
        FROM articles
        WHERE articles.published_at IS NOT NULL
        AND articles.category = 'actualites'
      ";

      $stmt = $repository->getConnection()->prepare($query);
      $stmt->execute();
      $listArticles = $stmt->fetchAll();

      for ($i = 0; $i < count($listArticles); $i++)
      {
        $listArticles[$i]['content'] = substr($listArticles[$i]['content'], 55, 255);

        if (strlen($listArticles[$i]['content']) === 255) {
          $listArticles[$i]['content'] = $listArticles[$i]['content'] . "...";
        }

      }

        return $this->render('HomeBundle:Display:event.html.twig', array(
          'listArticles' => $listArticles
        ));
    }

    public function newsAction()
    {
      $repository = $this
        ->getDoctrine()
        ->getManager();

        // $query = $repository->createQueryBuilder('a')
        //   ->where("a.category = 'on-en-a-parle'")
        //   ->andWhere('a.publishedAt IS NOT NULL')
        //   ->getQuery()
        // ;

        $query = "
          SELECT articles.id,
                 articles.title,
                 articles.content,
                 articles.published_at,
                 (
                   SELECT COUNT(*)
                   FROM comments
                   WHERE comments.article_id = articles.id
                 ) AS nb_comments
          FROM articles
          WHERE articles.published_at IS NOT NULL
          AND articles.category = 'on-en-a-parle'
        ";

        $stmt = $repository->getConnection()->prepare($query);
        $stmt->execute();
        $listArticles = $stmt->fetchAll();

        for ($i = 0; $i < count($listArticles); $i++)
        {
          $listArticles[$i]['content'] = substr($listArticles[$i]['content'], 55, 255);

          if (strlen($listArticles[$i]['content']) === 255) {
            $listArticles[$i]['content'] = $listArticles[$i]['content'] . "...";
          }

        }

        return $this->render('HomeBundle:Display:news.html.twig', array(
          'listArticles' => $listArticles
        ));
    }

    public function showAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:Article');

        $article = $repository->find($id);

        $comments = $this->getDoctrine()->getRepository('HomeBundle:Comment')
                      ->findBy(array('articleId' => $article->getId()));

        $comment = new Comment();

        // On crée le FormBuilder grâce au service form factory
        $form = $this->get('form.factory')->createBuilder(FormType::class, $comment)
          ->setAction($this->generateUrl('home_createcomment'))
          ->add('article_id', HiddenType::class, array('data' => $article->getId()))
          ->add('name',     TextType::class)
          ->add('content', TextareaType::class)
          ->add('save',      SubmitType::class)
          ->getForm()
        ;

        return $this->render('HomeBundle:Display:article.html.twig',
          array('article' => $article, 'comments' => $comments, 'form' => $form->createView()));
    }

    // // public function countComment()
    // // {
    // //   $repository = $this->getDoctrine()->getRepository('AdminBundle:Article');
    // //
    // //   $article = $repository->find($id);
    // //
    // //   $comments = $this->getDoctrine()->getRepository('HomeBundle:Comment')
    // //                 ->findBy(array('articleId' => $article->getId()));
    // //
    // //   $nbrComments = 0;
    // //
    // //   for ($i = 0; i < count($comments); i++) {
    // //     $nbrComments++;
    // //   }
    // }

}
