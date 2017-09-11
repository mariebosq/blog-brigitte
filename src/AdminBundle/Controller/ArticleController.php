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
use Symfony\Component\Validator\Constraints\DateTime;

class ArticleController extends Controller
{
    public function indexAction()
    {
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('AdminBundle:Article')
        ;

        $listArticles = $repository->findAll();

        return $this->render('AdminBundle:Article:index.html.twig', array(
          'listArticles' => $listArticles
        ));
    }

    public function showAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:Article');

        $article = $repository->find($id);

        return $this->render('AdminBundle:Article:article.html.twig', array('article' => $article));
    }

    public function createAction(Request $request)
    {
    // On crée un objet Article
    $article = new Article();

    // On crée le FormBuilder grâce au service form factory
    $form = $this->get('form.factory')->createBuilder(FormType::class, $article)
      ->add('createdAt',      DateType::class)
      ->add('slug',     TextType::class)
      ->add('title',     TextType::class)
      ->add('content',   TextareaType::class)
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
        ->add('slug',     TextType::class)
        ->add('title',     TextType::class)
        ->add('content',   TextareaType::class)
        //->add('published', CheckboxType::class, array('required' => false))
        ->add('save',      SubmitType::class)
        ->getForm()
      ;

      // À ce stade, le formulaire n'est pas valide car :
      // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
      // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
      return $this->render('AdminBundle:Article:new.html.twig', array(
        'form' => $form->createView(),
      ));
  }

  public function editAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $article= $em->getRepository('AdminBundle:Article')->find($id);

    // On crée le FormBuilder grâce au service form factory
    $form = $this->get('form.factory')->createBuilder(FormType::class, $article)
      ->setAction($this->generateUrl('admin_update_article', ['id' => $article->getId()], true ))
      ->setMethod('PUT')
      ->add('createdAt',      DateType::class)
      ->add('slug',     TextType::class)
      ->add('title',     TextType::class)
      ->add('content',   TextareaType::class)
      //->add('published', CheckboxType::class, array('required' => false))
      ->add('save',      SubmitType::class)
      ->getForm()
    ;

    return $this->render('AdminBundle:Article:edit.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function updateAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $article= $em->getRepository('AdminBundle:Article')->find($id);

    $article->setTitle($request->request->get('form')['title']);
    $article->setContent($request->request->get('form')['content']);

    $em->flush();

    return $this->redirectToRoute('admin_homepage');
  }

  public function publishAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $article= $em->getRepository('AdminBundle:Article')->find($id);

    $now = new \DateTime();
    $article->setPublishedAt($now);

    $em->flush();

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
