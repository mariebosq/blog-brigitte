<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Repository\ArticlesRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\DateTime;
use AdminBundle\Service\FileUploader;

class ArticleController extends Controller
{
    public function indexAction(Request $request)
    {
      // On recupere la page courante
      $page = $request->query->get('page');

      // Si celle-ci n'est pas precisée (par defaut), on la met a 1
      if ($page == NULL) {
        $page = 1;
      }

      $em = $this->getDoctrine()->getManager();

      $articles = $em->getRepository('AdminBundle:Article')
          ->findAllPagineEtTrie($page, 10);

      $pagination = array(
          'page' => $page,
          'nbPages' => ceil(count($articles) / 10),
          'nomRoute' => 'admin_homepage',
          'paramsRoute' => array()
      );

      return $this->render('AdminBundle:Article:index.html.twig', array(
          'listArticles' => $articles,
          'pagination' => $pagination
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
      ->add('slug',     TextType::class)
      ->add('category',     ChoiceType::class, array(
        'choices' => array(
          'En Images'     => 'en-images',
          'On en a parlé' => 'on-en-a-parle',
          'Évenements'    => 'evenements'
        )
      ))
      ->add('title',     TextType::class)
      ->add('content',   TextareaType::class)
      ->add('save',      SubmitType::class)
      ->getForm()
    ;
    $now = new \DateTime();
    $article->setCreatedAt($now);

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

  public function newAction(FileUploader $fileUploader) {
      // On crée un objet Article
      $article = new Article();

      // On crée le FormBuilder grâce au service form factory
      $form = $this->get('form.factory')->createBuilder(FormType::class, $article)
        ->setAction($this->generateUrl('admin_create_article'))
        ->add('slug',     TextType::class)
        ->add('category',     ChoiceType::class, array(
          'choices' => array(
            'En Images'     => 'en-images',
            'On en a parlé' => 'on-en-a-parle',
            'Évenements'    => 'evenements'
          )
        ))
        ->add('title',     TextType::class)
        ->add('content',   TextareaType::class)
        ->add('save',      SubmitType::class)
        ->getForm()
      ;

      if ($form->isSubmitted() && $form->isValid()) {
        $file = $article->getBrochure();
        $fileName = $fileUploader->upload($file);

        $article->setBrochure($fileName);

      // À ce stade, le formulaire n'est pas valide car :
      // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
      // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
      return $this->render('AdminBundle:Article:new.html.twig', array(
        'form' => $form->createView(),
      ));
  }
}  

  public function editAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $article= $em->getRepository('AdminBundle:Article')->find($id);

    // On crée le FormBuilder grâce au service form factory
    $form = $this->get('form.factory')->createBuilder(FormType::class, $article)
      ->setAction($this->generateUrl('admin_update_article', ['id' => $article->getId()], true ))
      ->setMethod('PUT')
      ->add('slug',         TextType::class)
      ->add('category',     ChoiceType::class, array(
        'choices' => array(
          'En Images'     => 'en-images',
          'On en a parlé' => 'on-en-a-parle',
          'Évenements'    => 'evenements'
        )
      ))
      ->add('title',        TextType::class)
      ->add('content',   TextareaType::class)
      ->add('save',         SubmitType::class)
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

  public function depublishAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $article= $em->getRepository('AdminBundle:Article')->find($id);

    $article->setPublishedAt(NULL);

    $em->flush();

    return $this->redirectToRoute('admin_homepage');
  }

  public function listAction($page)
    {
        $nbArticlesParPage = $this->container->getParameter('front_nb_articles_par_page');

        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AdminBundle:Article')
            ->findAllPagineEtTrie($page, $nbArticlesParPage);

        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($articles) / $nbArticlesParPage),
            'nomRoute' => 'admin_list_article',
            'paramsRoute' => array()
        );

        var_dump($pagination);

        return $this->render('AdminBundle:Article:index.html.twig', array(
            'articles' => $articles,
            'pagination' => $pagination
        ));
    }
}
