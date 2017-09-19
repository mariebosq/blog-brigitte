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
    $comment = new Comment();

    // On crée le FormBuilder grâce au service form factory
    $form = $this->get('form.factory')->createBuilder(FormType::class, $comment)
      ->add('article_id',     HiddenType::class)
      ->add('name',     TextType::class)
      ->add('content', TextareaType::class)
      ->add('save',      SubmitType::class)
      ->getForm()
    ;

    $now = new \DateTime();
    $comment->setPublishedAt($now);

    // On fait le lien Requête <-> Formulaire
    // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
    $form->handleRequest($request);

    if ($form->isValid()) {
    // On vérifie que les valeurs entrées sont correctes
    // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      // On enregistre notre objet $advert dans la base de données, par exemple
      $em = $this->getDoctrine()->getManager();
      $em->persist($comment);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      return $this->redirectToRoute('home_homepage');
    } else {
      // Gérer l'erreur ici
    }

  }
}
