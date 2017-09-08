<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('AdminBundle:Article')
        ;

        $listArticles = $repository->findAll();

        return $this->render('AdminBundle:Default:index.html.twig', array(
          'listArticles' => $listArticles
        ));
    }

    public function showAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:Article');

        $article = $repository->find($id);

        return $this->render('AdminBundle:Default:article.html.twig', array('article' => $article));
    }

    public function createAction(Request $request)
    {
    // On crée un objet Article
    $article = new Article();

    // On crée le FormBuilder grâce au service form factory
    $form = $this->get('form.factory')->createBuilder(FormType::class, $article)
      ->add('createdAt',      DateType::class)
      ->add('title',     TextType::class)
      ->add('content',   TextareaType::class)
      //->add('published', CheckboxType::class, array('required' => false))
      ->add('save',      SubmitType::class)
      ->getForm()
    ;

    // // Si la requête est en POST
    // if ($request->isMethod('POST')) {

    // On fait le lien Requête <-> Formulaire
    // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
    $form->handleRequest($request);

    // On vérifie que les valeurs entrées sont correctes
    // (Nous verrons la validation des objets en détail dans le prochain chapitre)
    if ($form->isValid()) {
      // On enregistre notre objet $advert dans la base de données, par exemple
      $em = $this->getDoctrine()->getManager();
      $em->persist($article);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // On redirige vers la page de visualisation de l'annonce nouvellement créée
      return $this->redirectToRoute('admin_show_article', array('id' => $article->getId()));
    }
  }

  public function newAction() {
      // On crée un objet Article
      $article = new Article();

      // On crée le FormBuilder grâce au service form factory
      $form = $this->get('form.factory')->createBuilder(FormType::class, $article)
        ->setAction($this->generateUrl('admin_create_article'))
        ->add('createdAt',      DateType::class)
        ->add('title',     TextType::class)
        ->add('content',   TextareaType::class)
        //->add('published', CheckboxType::class, array('required' => false))
        ->add('save',      SubmitType::class)
        ->getForm()
      ;

      // À ce stade, le formulaire n'est pas valide car :
      // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
      // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
      return $this->render('AdminBundle:Default:new.html.twig', array(
        'form' => $form->createView(),
      ));
  }

  public function editAction($id)
  {
    $request = $this->get('request');

    if (is_null($id)) {
        $postData = $request->get('Article');
        $id = $postData['id'];
    }

    $em = $this->getDoctrine()->getEntityManager();
    $article= $em->getRepository('AdminBundle:Article')->find($id);
    $form = $this->createForm(new ArticleType(), $article);

    if ($request->getMethod() == 'POST') {
        $form->bindRequest($request);

        if ($form->isValid()) {
            // perform some action, such as save the object to the database
            $em->flush();

            return $this->redirectToRoute('admin_articlepage');
        }
    }

    return $this->render('AdminBundle:Default:edit.html.twig', array(
        'form' => $form->createView()
    ));
  }

  public function updateAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $article= $em->getRepository('AdminBundle:Article')->find($id);

    // Update here

    return $this->redirectToRoute('admin_homepage');
  }

  public function deleteAction($id)
  {
      $em = $this->getDoctrine()->getEntityManager();
      $article = $em->getRepository('AdminBundle:Article')->find($id);

      $em->remove($article);
      $em->flush();

      return $this->redirectToRoute('admin_homepage');
  }
}
