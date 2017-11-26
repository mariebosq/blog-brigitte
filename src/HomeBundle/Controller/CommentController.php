<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Article;
use HomeBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


use Symfony\Component\Debug\Debug;


class CommentController extends Controller
{
  public function createAction(Request $request)
  {
    // On initialize le nouveau commentaire
    $now = new \DateTime();
    $comment = new Comment();

    // On remplit les valeurs
    $comment->setArticleId($request->request->get('form')['article_id']);
    $comment->setContent($request->request->get('form')['content']);
    $comment->setName($request->request->get('form')['name']);
    $comment->setPublishedAt($now);

    // On le sauvegarde
    $em = $this->getDoctrine()->getManager();
    $em->persist($comment);
    $em->flush();

    // On recupere l'article associé
    $article= $em->getRepository('AdminBundle:Article')->find($request->request->get('form')['article_id']);
    $comments = $this->getDoctrine()->getRepository('HomeBundle:Comment')
                  ->findBy(array('articleId' => $article->getId()));

    // On réaffiche le FormBuilder grâce au service form factory
    $form = $this->get('form.factory')->createBuilder(FormType::class, $comment)
      ->setAction($this->generateUrl('home_createcomment'))
      ->add('article_id', HiddenType::class, array('data' => $article->getId()))
      ->add('name',       TextType::class)
      ->add('content',    TextareaType::class)
      ->add('save',       SubmitType::class)
      ->getForm()
    ;

    return $this->render('HomeBundle:Display:article.html.twig',
      array('article' => $article, 'comments' => $comments, 'form' => $form->createView()));
  }
}
